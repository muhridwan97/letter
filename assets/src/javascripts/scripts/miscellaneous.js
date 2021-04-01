$(function () {
    // mobile sidebar
    $('[data-toggle="offcanvas"]').on("click", function () {
        $('.sidebar-offcanvas').toggleClass('active')
    });

    // checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    // initialize date picker
	/* $('.datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: false,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }).on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format(picker.locale.format));
    }); */

    function initDatepicker() {
		const dateFormat = 'DD/MM/YYYY';
		const fullDateFormat = 'DD MMMM YYYY';
		$('.datepicker:not([readonly])').each(function () {
			const options = {
				singleDatePicker: true,
				showDropdowns: true,
				drops: $(this).closest('#modal-filter').length ? 'up' : 'down',
				parentEl: $(this).closest('#modal-filter').length ? '#modal-filter' : 'body',
				autoUpdateInput: false,
				locale: {
					format: dateFormat
				},
			};
			if ($(this).data('min-date')) {
				options.minDate = $(this).data('min-date');
			}
			if ($(this).data('max-date')) {
				options.maxDate = $(this).data('max-date');
			}
			if ($(this).data('parent-el')) {
				options.parentEl = $(this).data('parent-el');
			}
			if ($(this).data('locale-format')) {
				options.locale.format = $(this).data('locale-format');
			}
			if ($(this).data('drops')) {
				options.drops = $(this).data('drops');
			}
			$(this).daterangepicker(options)
				.on("apply.daterangepicker", function (e, picker) {
					picker.element.val(picker.startDate.format(picker.locale.format));

					// update another picker element
					const updateTarget = $(picker.element).data('update-target');
					const updateMin = $(picker.element).data('update-min');
					if (updateTarget) {
						const pickedDate = moment(picker.startDate, picker.locale.format); // must be moment object
						if (updateMin) {
							if (Number(updateMin) > 0) {
								pickedDate.add(Number(updateMin || 0), 'd');
							} else if (Number(updateMin) < 0) {
								pickedDate.subtract(Number(updateMin.toString().replace(/[^0-9]/g, "") || 0), 'd');
							}
						}
						$(updateTarget).data('daterangepicker').minDate = pickedDate;

						// set blank target if minimum date greater than current value
						if (pickedDate.isAfter(moment($(updateTarget).val(), dateFormat))) {
							$(updateTarget).val('').focus();
						}
					}
				})
				.on("hide.daterangepicker", function (e, picker) {
					setTimeout(function () {
						const formattedDate = $(picker.element).closest('.form-group').find('.formatted-date');
						if (picker.element.val()) {
							formattedDate.text(picker.startDate.format(fullDateFormat));
						} else {
							formattedDate.text('(Pick a date)');
						}
					}, 150);
				});
		});
	}
	initDatepicker();
    window.initDatepicker = initDatepicker;

    // Select2
    const selects = $('.select2');
    selects.each(function (index, select) {
        $(select).select2({
            minimumResultsForSearch: 10,
            placeholder: 'Select data'
        }).on("select2:open", function () {
            $(".select2-search__field").attr("placeholder", "Search...");
        }).on("select2:close", function () {
            $(".select2-search__field").attr("placeholder", null);
        });
    });

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });

    // Popover
    $('[data-toggle="popover"]').popover();

});
