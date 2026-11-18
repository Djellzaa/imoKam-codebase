/**
 * Pagination Click Fix - Improved & Safe Version
 * Fixed AJAX data handling + removed aggressive event blocking
 */

(function () {
    'use strict';

    if (typeof jQuery === 'undefined') {
        console.warn('jQuery not available for pagination fix');
        return;
    }

    jQuery(function ($) {

        /**
         * PAGINATION CLICK HANDLER
         */
        $(document).off('click.paginationFix')
            .on('click.paginationFix', '.flat-pagination a, .filter-form .flat-pagination a', function (e) {

                var href = this.href;
                var $link = $(this);
                var $form = $link.closest('form');

                if (!href) return;

                // If no form → allow normal navigation
                if (!$form.length) {
                    return;
                }

                e.preventDefault();

                var pageNum = null;

                try {
                    pageNum = new URL(href).searchParams.get('page');
                } catch (err) {
                    console.warn('Could not parse pagination URL:', href);
                }

                // If NOT filter-form → submit normally
                if (!$form.hasClass('filter-form')) {
                    if (pageNum !== null) {
                        $form.find('input[name="page"]').val(pageNum);
                    }
                    $form.trigger('submit');
                    return;
                }

                // FILTER FORM AJAX
                if (pageNum !== null) {
                    $form.find('input[name="page"]').val(pageNum);
                }

                var serializedData = $form.serialize();
                var $dataListing = $('[data-bb-toggle="data-listing"]');

                $.ajax({
                    url: href,
                    type: 'GET',
                    data: serializedData,
                    beforeSend: function () {
                        if ($dataListing.length) {
                            $dataListing.append('<div class="loading-spinner"></div>');
                        }
                    },
                    success: function (response) {

                        if ($dataListing.length) {
                            $dataListing.html(response);
                        }

                        // Lazy load update
                        if (typeof Theme !== 'undefined' && Theme.lazyLoadInstance) {
                            Theme.lazyLoadInstance.update();
                        }

                        // Map update
                        if (typeof initMap === 'function') {
                            initMap();
                        }

                        // Update browser URL
                        if (href !== window.location.href) {
                            window.history.pushState({}, '', href);
                            $('.reset-filter-btn').show();
                        }
                    },
                    complete: function () {
                        if ($dataListing.length) {
                            $dataListing.find('.loading-spinner').remove();
                        }
                    },
                    error: function () {
                        // Fallback to normal navigation
                        window.location.href = href;
                    }
                });
            });


        /**
         * FILTER FORM SUBMIT HANDLER
         */
        $(document).off('submit.paginationFix')
            .on('submit.paginationFix', '.filter-form', function (e) {

                e.preventDefault();

                var $form = $(this);
                var serializedData = $form.serialize();
                var actionUrl = $form.prop('action');
                var $dataListing = $('[data-bb-toggle="data-listing"]');

                $.ajax({
                    url: actionUrl,
                    type: 'GET',
                    data: serializedData,
                    beforeSend: function () {
                        if ($dataListing.length) {
                            $dataListing.append('<div class="loading-spinner"></div>');
                        }
                    },
                    success: function (response) {

                        if ($dataListing.length) {
                            $dataListing.html(response);
                        }

                        if (typeof Theme !== 'undefined' && Theme.lazyLoadInstance) {
                            Theme.lazyLoadInstance.update();
                        }

                        if (typeof initMap === 'function') {
                            initMap();
                        }

                        // Update URL with query string
                        var newUrl = actionUrl + '?' + serializedData;
                        window.history.pushState({}, '', newUrl);

                        $('.reset-filter-btn').show();
                    },
                    complete: function () {
                        if ($dataListing.length) {
                            $dataListing.find('.loading-spinner').remove();
                        }
                    },
                    error: function () {
                        $form.off('submit').submit();
                    }
                });
            });

        console.log('Pagination click handler installed (Improved Version)');
    });

})();