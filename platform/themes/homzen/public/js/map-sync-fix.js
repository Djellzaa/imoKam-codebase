/**
 * Map Sync Fix – simply forwards to the theme's map initializer.
 * The original `initMap` already handles showing only the currently
 * visible properties (including after AJAX pagination), so we don't
 * need to duplicate that logic here.  Leaving the stub in place makes
 * it easy to add extra behavior later if required, but for now it just
 * calls the original function.
 */

(function() {
    'use strict';

    if (typeof window.initMap !== 'function') {
        console.warn('Map Sync Fix: original initMap not found, skipping');
        return;
    }

    var originalInitMap = window.initMap;

    window.initMap = function(formData) {
        // delegate completely to the original implementation
        return originalInitMap.call(this, formData);
    };

    console.log('[Map Sync Fix] delegated initMap to original implementation');
})();
