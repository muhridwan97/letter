export default function () {
	const btnAddPeriod = $('#btn-add-period');
	const tablePeriodFilter = $('#table-period-filter');
	const btnTemplate1Month = $('#template-1-month');
	const btnTemplate3Month = $('#template-3-month');
	const btnTemplate4Month = $('#template-4-month');
	const btnTemplate6Month = $('#template-6-month');
	const selectYear = $('#year');
	const btnWeek = $('#week');

	var lastTemplate = btnTemplate1Month;

	function addPeriodRow(dateFrom = '', dateTo = '', disabled = false) {
		const lastRow = tablePeriodFilter.find('tbody tr').not('.row-placeholder').length;
		const templateRow = `
			<tr class="row-period">
				<td class="text-center label-order">${lastRow + 1}</td>
				<td>
					<input type="text" class="form-control datepicker input-from" name="dates[][from]" data-update-target="#period-to-${lastRow + 1}" 
						placeholder="Pick from date" aria-label="Date from" value="${dateFrom}" required>
				</td>
				<td>
					<input type="text" class="form-control datepicker input-from" name="dates[][to]" id="period-to-${lastRow + 1}" data-min-date="${dateFrom}"
						placeholder="Pick to date" aria-label="Date to" value="${dateTo}" required>
				</td>
				<td class="text-center">
					<button class="btn btn-sm btn-outline-danger btn-delete-period" type="button" ${disabled ? 'disabled style="pointer-events: none"' : ''}>
						<i class="mdi mdi-trash-can-outline"></i>
					</button>
				</td>
			</tr>
		`;
		tablePeriodFilter.find('tbody').first().append(templateRow);
	}

	btnAddPeriod.on('click', function () {
		addPeriodRow();
		reorderRowPeriods();
		initDatepicker();
	});

	tablePeriodFilter.on('click', '.btn-delete-period', function (e) {
		e.preventDefault();

		$(this).closest('tr').remove();

		reorderRowPeriods();
		initDatepicker();
	});

	function reorderRowPeriods() {
		tablePeriodFilter.find('tbody tr').not('.row-placeholder').each(function (index) {
			// recount row number
			$(this).find('.label-order').html((index + 1).toString());
			$(this).find('.input-from').data('update-target', '#period-to-' + (index + 1));
			$(this).find('.input-to').prop('id', 'period-to-' + (index + 1));

			// reorder index of inputs
			$(this).find('input[name]').each(function () {
				const pattern = new RegExp("dates[([0-9]*\\)?]", "i");
				const attributeName = $(this).attr('name').replace(pattern, 'dates[' + index + ']');
				$(this).attr('name', attributeName);
			});
		});
	}

	function groupMonthlyTemplate(totalGroup, rangeMonth, tgl = moment().year()) {
		tablePeriodFilter.find('.row-period').remove();
		const date = moment().year(tgl).startOf('year');
		for (let i = 1; i <= totalGroup; i++) {
			const startDate = date.startOf('month').format('DD/MM/YYYY');
			const endDate = date.add(rangeMonth - 1, 'months').endOf('month').format('DD/MM/YYYY');
			addPeriodRow(startDate, endDate, i === 1);
			date.add(1, 'months');
		}
		reorderRowPeriods();
		initDatepicker();
	}

	btnTemplate1Month.on('click', function () {
		groupMonthlyTemplate(12, 1, selectYear.val());
		lastTemplate = btnTemplate1Month;
	});

	btnTemplate3Month.on('click', function() {
		groupMonthlyTemplate(4, 3, selectYear.val());
		lastTemplate = btnTemplate3Month;
	});

	btnTemplate4Month.on('click', function() {
		groupMonthlyTemplate(3, 4, selectYear.val());
		lastTemplate = btnTemplate4Month;
	});

	btnTemplate6Month.on('click', function() {
		groupMonthlyTemplate(2, 6, selectYear.val());
		lastTemplate = btnTemplate6Month;
	});

	function groupWeekTemplate(rangeWeek, tgl = moment().year()) {
		tablePeriodFilter.find('.row-period').remove();
		const date = moment().year(tgl).startOf('year');
		var thisDay = moment().format('MMDDYYYY');
		var weeknumber = moment(thisDay, "MMDDYYYY").week();
		if(tgl != moment().year()){
			thisDay = moment(date).endOf('year').format('MMDDYYYY');
			weeknumber = moment(thisDay, "MMDDYYYY").isoWeek();
			if (weeknumber == 1) {
				weeknumber = moment(thisDay, "MMDDYYYY").add(-1, 'weeks').isoWeek() + 1;
			}
		}
		var totalGroup = weeknumber/rangeWeek;
		var tempSort = [];
		for (let i = 1; i <= totalGroup; i++) {
			const startDate = date.startOf('week').format('DD/MM/YYYY');
			const endDate = date.add(rangeWeek - 1, 'week').endOf('week').format('DD/MM/YYYY');
			// addPeriodRow(startDate, endDate, i === 1);
			let temp = {
				start_date : startDate,
				end_date : endDate,
			}
			tempSort.push(temp);
			date.add(1, 'week');
		}
		let index = tempSort.length - 1;
		for (index; index >= 0; index--) {
			const element = tempSort[index];
			addPeriodRow(element.start_date, element.end_date, index === (tempSort.length - 1));
		}
		reorderRowPeriods();
		initDatepicker();
	}

	btnWeek.on('click', function() {
		groupWeekTemplate(1, selectYear.val());
		lastTemplate = btnWeek;
	});

	selectYear.on('change', function () {
		lastTemplate.trigger('click');
	});
}
