const PXA_JOBS_FILTER = (function () {

	/**
	 * Check if value is in lsit of values
	 *
	 * @param haystack
	 * @param needle
	 * @return {boolean}
	 * @private
	 */
	const _isInList = function (haystack, needle) {
		return (',' + haystack + ',').indexOf(',' + needle + ',') !== -1;
	};

	/**
	 * Check if elem belongs to one of the categories
	 *
	 * @param $elem
	 * @param categories
	 * @return {boolean}
	 * @private
	 */
	const _matchCategorySet = function ($elem, categories) {
		let elemCategories = $elem.data('categories') || '';

		for (let i = 0; i < categories.length; i++) {
			if (_isInList(elemCategories, categories[i])) {
				return true;
			}
		}

		return false;
	};

	return {
		filterItems: function (queriesSet, $itemsList) {
			$itemsList.each(function () {
				let isVisible = true;

				for (let prop in queriesSet) {
					if (!queriesSet.hasOwnProperty(prop)) {
						continue;
					}

					if (!_matchCategorySet($(this), queriesSet[prop])) {
						isVisible = false;
						break;
					}
				}

				if (isVisible) {
					$(this).addClass('_item-visible');
				} else {
					$(this).removeClass('_item-visible')
				}
			})
		}
	}
})();

$(window).on('load', function () {
	let $itemsList = $('#js__filter-container').find('.filtering-item'),
		$filter = $('.js__category-filter');

	if ($itemsList.length && $filter.length) {
		let queriesSet = {};

		// If active category is set
		if (window.location.hash
			&& window.location.hash.lastIndexOf('#category', 0) === 0
		) {
			let activeCategory = window.location.hash.substring(10); // Cut 'category=' to get just id
			if (activeCategory.length) {
				queriesSet['activeCategory'] = [activeCategory];
				$filter
					.find('.category-filter__list-item[data-value="' + activeCategory + '"]')
					.addClass('_active');
			}
		}

		// Init first filtering
		PXA_JOBS_FILTER.filterItems(queriesSet, $itemsList);

		// On filter click
		$filter.find('.category-filter__list-item').on('click', function () {
			let queriesSet = {},
				$this = $(this);

			$this.toggleClass('_active');

			$filter.find('.category-filter__list-item._active').each(function () {
				let set = $(this).data('filter-set') || 0;

				if (typeof queriesSet[set] === 'undefined') {
					queriesSet[set] = [];
				}
				queriesSet[set].push($(this).data('value'));
			});

			PXA_JOBS_FILTER.filterItems(queriesSet, $itemsList);
		})
	}
});

