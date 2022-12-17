<?php
class CreateSubmissionEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'forms/{form}/submissions/create';
        $this->_module = 'Formlar';
        $this->_description = 'Create a new form submission';
        $this->_method = 'POST';
    }

    public function execute(Radome2API $api, Form $form): void {
        $api->validateParams($_POST, ['field_values']);

        $user != null;
        if (isset($_POST['user'])) {
            $user = $this::transformUser($api, $_POST['user']);
        }

        $validation = $form->validateFields($_POST['field_values'], Formlar::getLanguage(), $api->getLanguage());
        if (!$validation->passed()) {
            // Validation errors
            $api->throwError(FormlarApiErrors::ERROR_VALIDATION_ERRORS, $validation->errors());
        }

        $submission = new Submission();
        if (!$submission->create($form, $user, $_POST['field_values'])) {
            $api->throwError(FormlarApiErrors::ERROR_UNKNOWN_ERROR, $submission->getErrors());
        }

        $api->returnArray([
            'submission_id' => $submission->data()->id,
            'link' => rtrim(URL::getSelfURL(), '/') . URL::build('/kullanici/talepler/', 'view=' . Output::getClean($submission->data()->id))
        ]);
    }

    private function transformUser(Radome2API $api, string $value) {
        return Endpoints::getAllTransformers()['user']['transformer']($api, $value);
    }
}