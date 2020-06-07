
$('body').on('dblclick', '.grid-view tr', function(event){
	var dataKey = $(this).attr('data-key');
	var dataArray = new Array;
	dataArray.push(dataKey);
	var row = $(this);
	row.addClass("danger");
	console.log(row);
	$.post({
		url: '/feed/feed-data/set-inactive',
		dataType: 'json',
		data: { syncArray: dataArray },
		success: function(data){
			console.log("Return Code: " + data['count']);
			if(data['count'] == 1) {
				row.remove();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.responseText);
		},
	});
});
