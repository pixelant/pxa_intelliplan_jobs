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
		this.scrollButton = $(settings.scrollButton);
		this.settings = settings;
	}

	PxaIntelliplanJobs.prototype = {
		/**
		 * Init
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

			// Init file upload buttons actions
			this.initFileUploadActions($(this.settings.fileUploadInput), $(this.settings.fileUploadClear));

			// Allow to add files uploads to form
			if (this.addAdditionalFileButton.length > 0) {
				this.addAdditionalFileButton.on('click', function (e) {
					e.preventDefault();

					that.addAdditionalFile();
				});
			}

			// Scroll to apply to job form
			this.scrollButton.on('click', function (e) {
				e.preventDefault();

				that.scrollToSmooth($(this).data('scroll-to'), $(this).data('scroll-fix'));
			});
		},

		/**
		 * Add more files upload to form
		 */
		addAdditionalFile: function () {
			if (this.additonalFilesCounter === 0) {
				// Just make visible template
				this.additionalFileTemplate.removeClass('hidden');
				this.additionalFileLastItem = this.additionalFileTemplate;
			} else {
				let additionalFile = this.additionalFileTemplate.clone();

				this.resetNewUploadElement(additionalFile);
				this.setNewAttributeNamesForNewUploadElement(additionalFile);

				this.initFileUploadActions(
					additionalFile.find(this.settings.fileUploadInput),
					additionalFile.find(this.settings.fileUploadClear),
					true // Reset values
				);

				this.additionalFileLastItem.after(additionalFile);
				this.additionalFileLastItem = additionalFile;
			}

			this.additonalFilesCounter++;
		},

		/**
		 * Reset new upload element
		 *
		 * @param fileElement
		 */
		resetNewUploadElement: function(fileElement) {
			// Reset error class
			fileElement
				.find('.' + this.settings.errorFieldClass)
				.removeClass(this.settings.errorFieldClass);

			// Remove errors
			fileElement
				.find('.text-danger')
				.remove();
			// Reset id
			fileElement.removeAttr('id');
		},

		/**
		 * Set new values for upload element
		 *
		 * @param fileElement
		 */
		setNewAttributeNamesForNewUploadElement: function(fileElement) {
			let newLabel = fileElement.data('label').replace(
				this.settings.additionalFilesCounterPlaceHolder,
				this.additonalFilesCounter
			);
			let newName = fileElement.data('name').replace(
				this.settings.additionalFilesCounterPlaceHolder,
				this.additonalFilesCounter
			);
			let newNameInput = 'tx_pxaintelliplanjobs_pi2[applyJobFiles][' + newName + ']';

			// Set new label
			fileElement
				.find(this.settings.fileLabelWrapper)
				.text(newLabel);

			// Set new data-field value
			fileElement
				.find('[data-field]')
				.addClass('red')
				.attr('data-field', newName);

			// Set new input name
			fileElement
				.find('input')
				.attr('name', newNameInput);
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
		 * Init add/remove file actions
		 *
		 * @param fileUploadInput
		 * @param fileUploadClear
		 * @param reset
		 */
		initFileUploadActions: function (fileUploadInput, fileUploadClear, reset) {
			let that = this;

			reset = reset || false;

			fileUploadInput.each(function () {
				$(this).on('change', function (e) {
					let $target = $(this).parent();
					$target.addClass('_touched');
					$target.find('.file-uploader__name').text(e.target.files[0].name);
				})
			});

			fileUploadClear.on('click', function () {
				$(this).siblings('._touched').removeClass('_touched');
				$(this).parent().find(that.settings.fileUploadInput)[0].value = '';
			});

			if (reset) {
				fileUploadClear.siblings('._touched').removeClass('_touched');
				fileUploadClear.parent().find(that.settings.fileUploadInput)[0].value = '';
			}
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
		},

		/**
		 * Scroll method
		 *
		 * @param target
		 * @param scrollFix
		 */
		scrollToSmooth: function (target, scrollFix) {
			let hash = target;
			target = $(target);

			let scrollFixParts = scrollFix.split('|'), // First value is for tables, second desktop;
				isDesktop = window.outerWidth >= 992;

			scrollFix = parseInt(isDesktop ? scrollFixParts[1] : scrollFixParts[0]);

			$('html, body').animate({
				scrollTop: $(hash).offset().top + scrollFix
			}, 800);
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
		fileUploadInput: '.js__file-upload',
		fileUploadClear: '.js__file-upload__clear',

		scrollButton: '[data-job-scroll="1"]'
	});
});