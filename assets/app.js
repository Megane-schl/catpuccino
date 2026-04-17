import './stimulus_bootstrap.js';
import 'bootstrap';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

// import bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import '@popperjs/core';

//import css of the APP
import './styles/app.css';

//flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { French } from 'flatpickr/dist/l10n/fr.js';

document.addEventListener('turbo:load', () => {
    const flatpickrFields = document.querySelectorAll('.flatpickr');
    if (flatpickrFields.length === 0) return;
    flatpickr('.flatpickr', {
        locale: French,
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
    });
});

// script for create/update a product : checkbox appears if checked
document.addEventListener('DOMContentLoaded', () => {

    const checkbox = document.querySelector('#product_form_isSeasonal');
    const seasonPeriod = document.querySelectorAll('.seasonal');

    if (!checkbox) return;

    //listen for any change in the checkbox's state : checked or unchecked
    checkbox.addEventListener('change', () => {

        // loop through each element in seasonPeriod
        seasonPeriod.forEach((datePeriod) => {

            // checks if the checkbox is checked
            if (checkbox.checked) {
                datePeriod.classList.remove('d-none');
            } else {
                datePeriod.classList.add('d-none');
            }
        });
    });
});
