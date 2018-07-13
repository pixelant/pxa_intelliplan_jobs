const PxaIntelliplanJobs = (function () {

	/**
	 * Initialize
	 * @param settings
	 * @constructor
	 */
	function PxaIntelliplanJobs(settings) {
		this.formTellFriend = $(settings.formTellFriend);
		this.formApplyJob = $(settings.formApplyJob);
		this.addAdditionalFileButton = $(settings.addAdditionalFileButton);
		this.additionalFileTemplate = $(settings.additionalFileTemplate);
		this.additionalFileLastItem = null;
		this.additonalFilesCounter = 0;
		this.settings = settings;
	}

	PxaIntelliplanJobs.prototype = {
		/**
		 * Init
		 * @returns {boolean}
		 */
		init: function () {
			let that = this;

			if (this.formTellFriend.length > 0) {
				this.formTellFriend.on('submit', function (e) {
					e.preventDefault();
					let $form = $(this);

					that.sendAjax($form, function (data) {
						let submit = $form.find('[type="submit"]');

						submit.before(that.getMessage(data.successMessage, true));
					});
				});
			}

			if (this.formApplyJob.length > 0) {
				this.formApplyJob.each(function () {
					let $form = $(this),
						$checkbox = $form.find(that.settings.acceptTerms);

					if ($checkbox.length > 0) {
						$checkbox.on('change', function () {
							that.acceptTermsAction($checkbox, $form);
						});
						// Check it on init
						that.acceptTermsAction($checkbox, $form);
					}
				});

				this.formApplyJob.on('submit', function (e) {
					e.preventDefault();
					let $form = $(this);

					if (that.isFormApplyJobSubmitAllowed($form)) {
						that.sendAjax($form, function (data) {
							$form.replaceWith(that.getMessage(data.successMessage, true));
						});
					}
				});
			}

			if (this.addAdditionalFileButton.length > 0) {
				this.addAdditionalFileButton.on('click', function (e) {
					e.preventDefault();

					that.addAdditionalFile();
				});
			}
		},

		/**
		 * Add more files upload to form
		 */
		addAdditionalFile: function () {
			if (this.additonalFilesCounter === 0) {
				// Just make visible template
				this.additionalFileTemplate.removeClass('hidden');
				this.additionalFileLastItem = this.additionalFileTemplate;
				this.setNewFileNameForAdditionalFile(this.additionalFileTemplate);
			} else {
				let additionalFile = this.additionalFileTemplate.clone();
				let label = additionalFile.data('label').replace(
					this.settings.additionalFilesCounterPlaceHolder,
					this.additonalFilesCounter + 1
				);
				let name = additionalFile.data('name').replace(
					this.settings.additionalFilesCounterPlaceHolder,
					this.additonalFilesCounter
				);

				this.setNewFileNameForAdditionalFile(additionalFile);

				this.additionalFileLastItem.after(additionalFile);
				this.additionalFileLastItem = additionalFile;
			}

			this.additonalFilesCounter++;
		},

		/**
		 * Set name of input for additional files
		 * @param fileElement
		 * @return {*}
		 */
		setNewFileNameForAdditionalFile: function(fileElement) {
			let name = fileElement.data('name').replace(
				this.settings.additionalFilesCounterPlaceHolder,
				this.additonalFilesCounter
			);
			let inputFile = fileElement.find('input'),
				newName = inputFile.attr('name').replace(this.settings.additionalDefaultFileName, name);

			inputFile.attr('name', newName);
		},

		/**
		 * When user accepted terms checkbox
		 */
		acceptTermsAction: function ($checkbox, $form) {
			let $submit = $form.find('[type="submit"]');

			$submit.prop('disabled', !$checkbox.is(':checked'));
		},

		/**
		 * Submit apply for a job
		 * @param $form
		 * @return bool
		 */
		isFormApplyJobSubmitAllowed: function ($form) {
			let $checkbox = $form.find(this.settings.acceptTerms);

			return $checkbox.length > 0 && $checkbox.is(':checked');
		},

		/**
		 * Actions on form share submit
		 */
		sendAjax: function (form, callback) {
			let loader = form.parent().find(this.settings.loader);

			loader.removeClass(this.settings.loaderReadyClass);

			let that = this,
				url = form.attr('action'),
				formData = new FormData(form[0]);
			console.log(form[0]);

			form.find('[type="submit"]').prop('disabled', true);
			form.find('.text-danger').remove();
			form.find('.' + that.settings.errorFieldClass)
				.removeClass(that.settings.errorFieldClass);

			$.ajax({
				type: 'POST',
				url: url,
				dataType: 'json',
				data: formData,
				enctype: 'multipart/form-data',
				processData: false,  // Important!
				contentType: false,
				cache: false
			})
				.done(function (data) {
					if (data.success) {
						form.find('[type="text"]').prop('disabled', true);

						if (typeof callback === 'function') {
							callback(data);
						}
					} else {
						for (let prop in data.errors) {
							if (!data.errors.hasOwnProperty(prop)) {
								continue;
							}

							let field = form.find('[data-field="' + prop + '"]');

							field.addClass(that.settings.errorFieldClass);
							for (let i = 0; i < data.errors[prop].length; i++) {
								field.prepend(that.getMessage(data.errors[prop][i]));
							}
						}

						form.find('[type="submit"]').prop('disabled', false);
					}
				})
				.always(function () {
					loader.addClass(that.settings.loaderReadyClass);
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
			return '<p class="' + (success ? 'text-success' : 'text-danger') + '">' + errorMessage + '</p>';
		}
	};

	/**
	 * Init method return
	 */
	return {
		init: function (settings) {
			let _instance = new PxaIntelliplanJobs(settings);
			_instance.init();

			return _instance;
		}
	}
})();

$(document).ready(function () {
	PxaIntelliplanJobs.init({
		formTellFriend: '.pxa-tell-friend',
		loader: '.ajax-loader',
		loaderReadyClass: '_ready',
		errorFieldClass: 'has-error',

		formApplyJob: 'form[name="apply-job"]',
		acceptTerms: '.acceptTerms',

		addAdditionalFileButton: '[data-add-document="1"]',
		additionalFileTemplate: '#pxa-additional-file-template',
		additionalFilesCounterPlaceHolder: '###COUNTER###',
		fileLabelWrapper: '[data-file-label="1"]',
		additionalDefaultFileName: 'file_default'
	});
});