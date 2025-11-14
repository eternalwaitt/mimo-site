// This function runs when the Google Sheets document is opened
function onOpen() {
    const ui = SpreadsheetApp.getUi(); // Get the user interface
    ui.createMenu('Custom Menu') // Create a new custom menu
        .addItem('Run Import', 'importMovimentacoesLoco') // Add an item to the menu
        .addToUi(); // Add the menu to the UI
}

// Set up the sheet structure (headers and formatting)
function setupMovimentacoes() {
    const ss = SpreadsheetApp.getActive();
    let sh = ss.getSheetByName('Movimentações');
    if (!sh) sh = ss.insertSheet('Movimentações');
    else sh.clearContents();

    const headers = [
        'Mês', 'Nickname', 'ID Contrato', 'Tipo Contrato', 'Valor Bruto (R$)',
        'Vivaz %', 'Vivaz R$', 'NF Vivaz',
        'Infl %', 'Infl R$', 'Pago Infl?', 'Data Pgto Infl', 'Comp. Infl', 'NF Infl',
        'Parc %', 'Parc R$', 'Parceiro Ind.', 'Pago Parc?', 'Data Pgto Parc', 'Comp. Parc', 'NF Parc', 'Link Invoice', 'Bonus', 'Valor Final'
    ];
    sh.getRange(1, 1, 1, headers.length).setValues([headers]);
    sh.setColumnWidths(1, headers.length, 120);

    // Formatação
    sh.getRange('A:A').setNumberFormat('@STRING@');
    sh.getRange('E:E').setNumberFormat('R$ #,##0.00');
    ['F', 'I', 'O'].forEach(c => sh.getRange(c + ':' + c).setNumberFormat('0.00%'));
    ['G', 'J', 'P'].forEach(c => sh.getRange(c + ':' + c).setNumberFormat('R$ #,##0.00'));
    sh.getRange('L:L').setNumberFormat('MM/dd/yyyy');
    sh.getRange('S:S').setNumberFormat('MM/dd/yyyy');

    // Validation and Conditional Formatting code remains the same as before
    // ...
}

// Main function to import data into Movimentações
function importMovimentacoesLoco() {
    const ss = SpreadsheetApp.getActive();
    const raw = ss.getSheetByName('Loco_Recebimentos');
    const mov = ss.getSheetByName('Movimentações');
    const ctr = ss.getSheetByName('Loco_Contratos');
    if (!raw || !mov || !ctr) return;

    // Cabeçalhos dinâmicos em Loco_Contratos
    const hdrCtr = ctr.getRange(1, 1, 1, ctr.getLastColumn()).getValues()[0];
    const iTipo = hdrCtr.indexOf('Contrato por Outra Agência?') + 1;
    const iObs = hdrCtr.indexOf('Observações') + 1;
    const iAg = hdrCtr.indexOf('Agência Responsável') + 1;
    const iCut = hdrCtr.indexOf('Cut VIVAZ') + 1;

    const [hdrRaw, ...rows] = raw.getDataRange().getValues();
    const start = mov.getLastRow() + 1;

    rows.forEach((r, i) => {
        const rr = i + 2;
        const row = start + i;

        // Process each row only if it's not already marked as processed
        const processed = r[hdrRaw.indexOf("Processed")]; // Assuming "Processed" column exists
        if (processed && processed === "Processed") return;

        // Start populating the sheet
        mov.getRange(row, 1).setFormula(`=TEXT(Loco_Recebimentos!A${rr},"MMMM yyyy")`);
        mov.getRange(row, 2).setFormula(`=Loco_Recebimentos!B${rr}`);
        mov.getRange(row, 3).setFormula(`=IFERROR(INDEX(Loco_Contratos!A:A, MATCH($B${row}, Loco_Contratos!B:B, 0)), "")`);
        mov.getRange(row, 4).setFormula(`=IF(INDEX(Loco_Contratos!$${String.fromCharCode(64 + iTipo)}:$${String.fromCharCode(64 + iTipo)}, MATCH($B${row}, Loco_Contratos!$B:$B, 0)) = "Sim", "Parceria", "Interno")`);
        mov.getRange(row, 5).setFormula(`=Loco_Recebimentos!D${rr}`);
        mov.getRange(row, 6).setFormula(`=IFERROR(INDEX(Loco_Contratos!$${String.fromCharCode(64 + iCut)}:$${String.fromCharCode(64 + iCut)}, MATCH($B${row}, Loco_Contratos!$B:$B, 0)), 0)`);
        mov.getRange(row, 7).setFormula(`=E${row} * F${row}`);
        mov.getRange(row, 8).setValue('');
        mov.getRange(row, 9).setValue(0.8);
        mov.getRange(row, 10).setFormula(`=E${row} * I${row}`);
        mov.getRange(row, 11).setValue('Não');
        mov.getRange(row, 12).setValue('');
        mov.getRange(row, 13).setValue('');
        mov.getRange(row, 14).setValue('');
        mov.getRange(row, 15).setFormula(`=MAX(0, 1 - F${row} - I${row})`);
        mov.getRange(row, 16).setFormula(`=E${row} * O${row}`);
        mov.getRange(row, 17).setFormula(`
      =LET(
        tipo, INDEX(Loco_Contratos!$${String.fromCharCode(64 + iTipo)}:$${String.fromCharCode(64 + iTipo)}, MATCH($B${row}, Loco_Contratos!$B:$B, 0)),
        obs, INDEX(Loco_Contratos!$${String.fromCharCode(64 + iObs)}:$${String.fromCharCode(64 + iObs)}, MATCH($B${row}, Loco_Contratos!$B:$B, 0)),
        ag, INDEX(Loco_Contratos!$${String.fromCharCode(64 + iAg)}:$${String.fromCharCode(64 + iAg)}, MATCH($B${row}, Loco_Contratos!$B:$B, 0)),
        IF(tipo = "Sim", ag, IF(LEN(obs) > 0, obs, ""))
      )
    `);
        mov.getRange(row, 18).setValue('Não');
        mov.getRange(row, 19).setValue('');
        mov.getRange(row, 20).setValue('');
        mov.getRange(row, 21).setValue('');
        mov.getRange(row, 22).setFormula(`=Loco_Recebimentos!E${rr}`);

        // Add Bonus (in USD or BRL) and include it in Final Value
        const bonus = r[hdrRaw.indexOf("Bonus")] || 0; // Assuming you have Bonus column in the raw data
        const valorFinal = r[hdrRaw.indexOf("Valor Final")] || 0; // This is the regular payment
        mov.getRange(row, 23).setValue(bonus); // Bonus column (column 23)
        mov.getRange(row, 24).setFormula(`=E${row} + $S${row}`); // Valor Final including Bonus
    });

    // Mark the rows as processed
    rows.forEach((r, i) => {
        const rr = i + 2;
        raw.getRange(rr, hdrRaw.indexOf("Processed") + 1).setValue("Processed");
    });
}
