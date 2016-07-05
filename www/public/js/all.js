(function (script) {

	script.construct = function() {
		script.confirms()
	}

	script.confirms = function() {
		$('.js-confirm').unbind('click')
		$('.js-confirm').on('click', function() {
			var text = "Are you sure?"
			if ($(this).data('confirm')) {
				var text = $(this).data('confirm')
			}

			var check = confirm(text)
			if (check == true) {
				return true
			}
			return false
		})
	}

})(window.script = window.script || {});
$(document).ready(script.construct);

//# sourceMappingURL=all.js.map
