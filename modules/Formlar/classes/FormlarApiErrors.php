<?php
/**
 * Contains namespaced API error messages for the Formlar module.
 * These have no versioning, and are not meant to be used by any other modules.
 *
 * @package Modules\Formlar
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class FormlarApiErrors {
    public const ERROR_FORM_NOT_FOUND = 'forms:form_not_found';
    public const ERROR_SUBMISSION_NOT_FOUND = 'forms:submission_not_found';
    public const ERROR_VALIDATION_ERRORS = 'forms:validation_errors';
    public const ERROR_UNKNOWN_ERROR = 'forms:unknown_error';
    public const ERROR_CANNOT_CHANGE_STATUS = 'forms:cannot_change_status';
}