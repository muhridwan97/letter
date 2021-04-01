$('.data-table').DataTable({
	language: {
		processing: "Loading...",
		searchPlaceholder: "Search order data"
	},
	pageLength: 100
});

const setTableViewport = function() {
    // screen.width
    if ($(window).width() > 768 ) {
        $('table.responsive .responsive-label').remove();
        $('table.responsive td').find('.dropdown').css('display', '');
    }
    else {
        $('table.responsive').each(function(i, table) {
            let head = [];
            $(table).find('>thead th').each(function(i, th) {
                head.push($(th).text());
            });
            $(table).find('tbody tr').each(function(i, tr) {
                if($(tr).find('td .responsive-label').length === 0) {
                    console.log($(tr).find('td').length, head.length);
                    if($(tr).find('td').length === head.length) {
                        $(tr).find('td').each(function(i, td) {
                            $(td).prepend(`<span class="responsive-label">${head[i]}</span>`)
                        });
                        $(tr).find('.dropdown').css('display', 'inline-block');
                    }
                }
            });
        });
    }
};

setTableViewport();

window.onresize = function() {
    setTableViewport();
};

export default setTableViewport;
