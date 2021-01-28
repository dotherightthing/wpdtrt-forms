/* frontend.js - generated 28/01/2021 at 16:15:40 from: */
/* 1 ./js/_polyfills.js */
/* 2 ./js/_frontend.js */

/**
 * @file js/_polyfills.js
 * @summary Front-end polyfills for MSIE.
 * @requires DTRT WordPress Plugin Boilerplate Generator 0.9.1
 */

/* eslint-disable */

/**
 * @function _forEach
 * @summary Polyfill for NodeList.prototype.forEach() (ES5)
 *
 * @see {@link https://developer.mozilla.org/en-US/docs/Web/API/NodeList/forEach}
 * @see {@link https://www.greycampus.com/blog/programming/java-script-versions}
 */
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
}

/**
 * @function _arrayIncludes
 * @summary Polyfill for Array.prototype.includes() (ES7, 2016)
 *
 * @see {@link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/includes}
 * @see {@link https://en.wikipedia.org/wiki/ECMAScript#7th_Edition_%E2%80%93_ECMAScript_2016}
 * @see {@link https://www.greycampus.com/blog/programming/java-script-versions}
 * @see {@link https://www.cluemediator.com/object-doesnt-support-property-or-method-includes-in-ie}
 */
if (!Array.prototype.includes) {
    Object.defineProperty(Array.prototype, 'includes', {
        value: function (searchElement, fromIndex) {

            if (this == null) {
                throw new TypeError('"this" is null or not defined');
            }

            // 1. Let O be ? ToObject(this value).
            var o = Object(this);

            // 2. Let len be ? ToLength(? Get(O, "length")).
            var len = o.length >>> 0;

            // 3. If len is 0, return false.
            if (len === 0) {
                return false;
            }

            // 4. Let n be ? ToInteger(fromIndex).
            //    (If fromIndex is undefined, this step produces the value 0.)
            var n = fromIndex | 0;

            // 5. If n ≥ 0, then
            //  a. Let k be n.
            // 6. Else n < 0,
            //  a. Let k be len + n.
            //  b. If k < 0, let k be 0.
            var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

            /**
             * @function sameValueZero
             * @summary Compares two values
             * @param {string} x - Value 1
             * @param {string} y - Value 2
             *
             * @returns {boolean} Whether the values match
             */
            function sameValueZero(x, y) {
                return x === y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
            }

            // 7. Repeat, while k < len
            while (k < len) {

                // a. Let elementK be the result of ? Get(O, ! ToString(k)).
                // b. If SameValueZero(searchElement, elementK) is true, return true.
                if (sameValueZero(o[k], searchElement)) {
                    return true;
                }

                // c. Increase k by 1.
                k++;
            }

            // 8. Return false
            return false;
        },
    });
};

/* eslint-enable */
/**
 * @file js/_frontend.js
 * @summary Scripting for the public front-end.
 * @description PHP variables are provided in wpdtrt_forms_config.
 * @requires DTRT WordPress Plugin Boilerplate Generator 0.9.1
 */

/* globals jQuery, wpdtrt_forms_config */
/* eslint-disable camelcase, no-unused-vars */

/**
 * jQuery object
 *
 * @external jQuery
 * @see {@link http://api.jquery.com/jQuery/}
 */

/**
 * @namespace wpdtrtFormsUi
 */
const wpdtrtFormsUi = {

    /**
     * Method: init
     *
     * Initialise front-end scripting.
     */
    // init: () => {}
};

jQuery(($) => {
    const config = wpdtrt_forms_config; // eslint-disable-line

    console.log('wpdtrtFormsUi.init'); // eslint-disable-line no-console
});
