export default {
    numberFormat: function (number, decimalsLength, decimalSeparator, thousandSeparator) {
        var n = number,
            decimalsLength = isNaN(decimalsLength = Math.abs(decimalsLength)) ? 0 : decimalsLength,
            decimalSeparator = decimalSeparator == undefined ? "," : decimalSeparator,
            thousandSeparator = thousandSeparator == undefined ? "." : thousandSeparator,
            sign = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(decimalsLength)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;

        return sign +
            (j ? i.substr(0, j) + thousandSeparator : "") +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousandSeparator) +
            (decimalsLength ? decimalSeparator + Math.abs(n - i).toFixed(decimalsLength).slice(2) : "");
    },
    getNumberValue: function (value) {
		const val = value.toString()
			.replace(/[^0-9,\-]/g, '')
			.replace(/,/, '.');
		return Number(val || 0);
    },
    setCurrencyValue: function (value, prefix, ths = '.', dec = ',', thsTarget = '.', decTarget = ',') {
		const signed = value.toString().match(/-/);
		const pattern = new RegExp("[^" + dec + "\\\d]", 'g');
		let number_string = value.toString().replace(pattern, '').toString(),
			splitDecimal = number_string.split(dec),
			groupThousand = splitDecimal[0].length % 3,
			currency = splitDecimal[0].substr(0, groupThousand),
			thousands = splitDecimal[0].substr(groupThousand).match(/\d{3}/gi);
		if (thousands) {
			let separator = groupThousand ? thsTarget : '';
			currency += separator + thousands.join(thsTarget);
		}
		currency = splitDecimal[1] !== undefined ? currency + decTarget + splitDecimal[1] : currency;
		return prefix + (signed ? '-' : '') +currency;
    }
};
