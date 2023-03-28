import $ from 'jquery';
import "./deleteUser.mjs";
import "./deleteUsers.mjs";
import "./fetchUsers.mjs";
import "./createSourceOfTruth.mjs";
import "./changeSourceOfTruth.mjs";
import {SOURCES_OF_TRUTH} from './utils/constants.mjs';

import "../css/style.css";
import "../css/checkbox.css";
import "../css/btn.css";

$(document).ready(function () {
    $('#check-all-btn').click(function () {
        $('.checkbox input').prop('checked', true);
    });

    $('#uncheck-all-btn').click(function () {
        $('.checkbox > input').prop('checked', false);
    });
});

document.cookie = `dataSource=${localStorage.getItem('source-of-truth') || SOURCES_OF_TRUTH[0].value}`