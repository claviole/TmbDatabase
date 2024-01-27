function openPrintWindow(
	workOrderNumber,
	ticketDetails,
	repairsMaintenanceDetails,
	lineDetails
) {
	var printWindow = window.open("", "PRINT", "height=800,width=1000");
	printWindow.document.write(
		"<html><head><title>" + workOrderNumber + "</title>"
	);
	printWindow.document.write("<style>");
	// Professional print styles
	printWindow.document.write(`
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; }
        h1 { font-size: 24px; text-align: center; margin-bottom: 0.5em; }
        h2 { font-size: 18px; color: #444; margin-top: 1em; margin-bottom: 0.25em; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; font-weight: bold; }
        td { background-color: #fff; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: center; font-size: 0.85em; }
        .print-container { margin: 20px; }
        .parts-needed { background-color: #dff0d8; }
        .ticket-details { font-size: 12px; } /* Smaller font size for ticket details */
        .repairs-maintenance { font-size: 18px; } /* Larger font size for repairs/maintenance */
        .repairs-maintenance input { height: 60px; font-size: 18px; width: 98%; } /* Even larger input boxes for handwriting */
    `);
	printWindow.document.write("</style>");
	printWindow.document.write("</head><body>");
	printWindow.document.write('<div class="print-container">'); // Container for print content
	printWindow.document.write("<h1>Work Order: " + workOrderNumber + "</h1>");

	// Ticket Details Section
	printWindow.document.write("<h2>Ticket Details</h2>");
	printWindow.document.write('<table class="ticket-details"><tr>');
	ticketDetails.forEach(function (field, index) {
		if (field.name === "Line Name" && lineDetails) {
			// Use the line details fetched from the server
			field.value = lineDetails.Line_Location + " - " + lineDetails.Line_Name;
		}
		printWindow.document.write(
			"<td><strong>" +
				field.name.replace(/_/g, " ") +
				":</strong> " +
				field.value +
				"</td>"
		);
		if ((index + 1) % 2 === 0) {
			printWindow.document.write("</tr><tr>");
		}
	});
	if (ticketDetails.length % 2 !== 0) {
		printWindow.document.write("<td></td></tr>"); // Add an empty cell to even out the last row
	}
	printWindow.document.write("</table>");

	// Repairs/Maintenance Section
	printWindow.document.write("<h2>Repairs/Maintenance Details</h2>");
	printWindow.document.write('<table class="repairs-maintenance">');
	repairsMaintenanceDetails.forEach(function (field) {
		if (field.name !== "orange_tag_id") {
			// Exclude the 'orange_tag_id' field
			printWindow.document.write(
				"<tr><th>" +
					field.name.replace(/_/g, " ") +
					'</th><td><input type="text" style="height: 80px; font-size: 22px; width: 98%;" value="' +
					field.value +
					'" /></td></tr>'
			);
		}
	});
	printWindow.document.write("</table>");
	// Footer Section
	printWindow.document.write(
		'<div class="footer">This Document is for Reference Only</div>'
	);

	printWindow.document.write("</div>"); // Close the print-container div
	printWindow.document.write("</body></html>");
	printWindow.document.close(); // necessary for IE >= 10
	printWindow.focus(); // necessary for IE >= 10

	// Wait for the content to be loaded before printing
	printWindow.onload = function () {
		printWindow.print();
		printWindow.close();
	};
}
