const PxaIntelliplanJobsTellFriend = (function () {

	/**
	 * Initialize
	 * @param settings
	 * @constructor
	 */
	function PxaIntelliplanJobsTellFriend(settings) {
		this.form = $(settings.form);

		if (this.form.length > 0) {
			this.loader = this.form.parent().find(settings.loader);
			this.messagesHolder = this.form.find(settings.messagesHolder);
			this.settings = settings;
		}
	}

	PxaIntelliplanJobsTellFriend.prototype = {
		/**
		 * Init
		 * @returns {boolean}
		 */
		init: function () {
			let that = this;

			if (this.form.length > 0) {
				this.form.on('submit', function (e) {
					e.preventDefault();

					that.formSubmit();
				});
			}
		},

		/**
		 * Actions on form submit
		 */
		formSubmit: function () {
			this.loader.removeClass(this.settings.loaderReadyClass);

			let that = this,
				url = this.form.attr('action');

			this.form.find('[type="submit"]').prop('disabled', true);
			this.form
				.find('.' + that.settings.errorFieldClass)
				.removeClass(that.settings.errorFieldClass);

			$.ajax({
				type: 'POST',
				url: url,
				dataType: 'json',
				data: that.form.serialize()
			})
				.done(function (data) {
					if (data.success) {
						that.messagesHolder.html(that.getMessage(data.successMessage, true));
					} else {
						for (let i = 0; i < data.errorFields.length; i++) {
							let field = that.form.find('[data-field="' + data.errorFields[i] + '"]');
							field.addClass(that.settings.errorFieldClass);
						}
						let messages = '';
						for (let i = 0; i < data.errors.length; i++) {
							messages += that.getMessage(data.errors[i]);
						}
						that.messagesHolder.html(messages);

						that.form.find('[type="submit"]').prop('disabled', false);
					}
				})
				.always(function () {
					that.loader.addClass(that.settings.loaderReadyClass);
				});
		},

		/**
		 * Add error to form
		 *
		 * @param errorMessage
		 * @param success
		 */
		getMessage: function (errorMessage, success) {
			success = success || false;
			return '<p class="'+ (success ? 'text-success' : 'text-danger') +'">' + errorMessage + '</p>';
		}
	};

	/**
	 * Init method return
	 */
	return {
		init: function (settings) {
			let _instance = new PxaIntelliplanJobsTellFriend(settings);
			_instance.init();

			return _instance;
		}
	}
})();

$(document).ready(function () {
	PxaIntelliplanJobsTellFriend.init({
		form: '.pxa-tell-friend',
		loader: '.loader',
		messagesHolder: '.messages-holder',
		loaderReadyClass: '_ready',
		errorFieldClass: 'has-error'
	});
});