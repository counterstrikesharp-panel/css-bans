document.addEventListener('DOMContentLoaded', function () {

    /**
     * 
     *  Stepper Default
     * 
     */

     var stepperWizardDefault = document.querySelector('.stepper-form-one');
     var stepperDefault = new Stepper(stepperWizardDefault, {
         animation: true
     })
     var stepperNextButtonDefault = stepperWizardDefault.querySelectorAll('.btn-nxt');
     var stepperPrevButtonDefault = stepperWizardDefault.querySelectorAll('.btn-prev');
 
     stepperNextButtonDefault.forEach(element => {
         element.addEventListener('click', function() {
             stepperDefault.next();
         })
     });
 
     stepperPrevButtonDefault.forEach(element => {
         element.addEventListener('click', function() {
             stepperDefault.previous();
         })
     });
 
     stepperWizardDefault.addEventListener('show.bs-stepper', function (event) {
         if (event.detail.from < event.detail.to) {
             stepperWizardDefault.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
         } else {
             stepperWizardDefault.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
         }
     })


     /**
      * 
      *  Vertical Default
      * 
      */
 
     var v_stepperWizardDefault = document.querySelector('.stepper-form-vertical');
     var v_stepperDefault = new Stepper(v_stepperWizardDefault, {
         animation: true
     })
     var v_stepperNextButtonDefault = v_stepperWizardDefault.querySelectorAll('.btn-nxt');
     var v_stepperPrevButtonDefault = v_stepperWizardDefault.querySelectorAll('.btn-prev');
 
     v_stepperNextButtonDefault.forEach(element => {
         element.addEventListener('click', function() {
             v_stepperDefault.next();
         })
     });
 
     v_stepperPrevButtonDefault.forEach(element => {
         element.addEventListener('click', function() {
             v_stepperDefault.previous();
         })
     });
 
     v_stepperWizardDefault.addEventListener('show.bs-stepper', function (event) {
         if (event.detail.from < event.detail.to) {
             v_stepperWizardDefault.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
         } else {
             v_stepperWizardDefault.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
         }
     })
    

    /**
     * 
     *  Stepper Icon
     * 
     */

    var stepperWizardIcon = document.querySelector('.stepper-icons');
    var stepperIcon = new Stepper(stepperWizardIcon, {
        animation: true
    })
    var stepperNextButtonIcon = stepperWizardIcon.querySelectorAll('.btn-nxt');
    var stepperPrevButtonIcon = stepperWizardIcon.querySelectorAll('.btn-prev');

    stepperNextButtonIcon.forEach(element => {
        element.addEventListener('click', function() {
            stepperIcon.next();
        })
    });

    stepperPrevButtonIcon.forEach(element => {
        element.addEventListener('click', function() {
            stepperIcon.previous();
        })
    });

    stepperWizardIcon.addEventListener('show.bs-stepper', function (event) {
        if (event.detail.from < event.detail.to) {
            stepperWizardIcon.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
        } else {
            stepperWizardIcon.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
        }
    })


    /**
     * 
     *  Vertical Icons
     * 
     */

    var v_stepperWizardIcon = document.querySelector('.stepper-vertical-icons');
    var v_stepperIcon = new Stepper(v_stepperWizardIcon, {
        animation: true
    })
    var v_stepperNextButtonIcon = v_stepperWizardIcon.querySelectorAll('.btn-nxt');
    var v_stepperPrevButtonIcon = v_stepperWizardIcon.querySelectorAll('.btn-prev');

    v_stepperNextButtonIcon.forEach(element => {
        element.addEventListener('click', function() {
            v_stepperIcon.next();
        })
    });

    v_stepperPrevButtonIcon.forEach(element => {
        element.addEventListener('click', function() {
            v_stepperIcon.previous();
        })
    });

    v_stepperWizardIcon.addEventListener('show.bs-stepper', function (event) {
        if (event.detail.from < event.detail.to) {
            v_stepperWizardIcon.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
        } else {
            v_stepperWizardIcon.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
        }
    })


    /**
     * 
     *  Validation Horizontal  
     * 
     */

    var formValidation = document.querySelector('.stepper-form-validation-one');
    var stepper = new Stepper(formValidation, {
        animation: true
    })
    var formValidationNextButton = formValidation.querySelectorAll('.btn-nxt');
    var formValidationPrevButton = formValidation.querySelectorAll('.btn-prev');
    var formValidationSubmit = formValidation.querySelector('.btn-submit');
    var stepperPanList = [].slice.call(formValidation.querySelectorAll('.content'))
    var inputName = formValidation.querySelector('#form-name');
    var inputEmail = formValidation.querySelector('#emailAddress');

    var inputAddress = formValidation.querySelector('#inputAddress');
    var inputCity = formValidation.querySelector('#inputCity');
    var inputState = formValidation.querySelector('#inputState');
    var inputZip = formValidation.querySelector('#inputZip');
    var gridCheck = formValidation.querySelector('#gridCheck');

    var formEl = formValidation.querySelector('.bs-stepper-content form')

    formValidationNextButton.forEach(element => {
        element.addEventListener('click', function() {
            stepper.next();
        })
    });

    formValidationPrevButton.forEach(element => {
        element.addEventListener('click', function() {
            stepper.previous();
        })
    });

    formValidation.addEventListener('show.bs-stepper', function (event) {
        formEl.classList.remove('was-validated')
        var nextStep = event.detail.indexStep
        var currentStep = nextStep
    
        if (currentStep > 0) {
          currentStep--
        }
        
        var stepperPan = stepperPanList[currentStep]

        if (
            (stepperPan.getAttribute('id') === 'step-one' && !inputName.value.length)
            ||
            (stepperPan.getAttribute('id') === 'step-two' && !inputEmail.value.length)
            ||
            (stepperPan.getAttribute('id') === 'step-three' && !inputAddress.value.length)
            ||
            (stepperPan.getAttribute('id') === 'step-three' && !inputCity.value.length)
            ||
            (stepperPan.getAttribute('id') === 'step-three' && !inputState.value.length)
            ||
            (stepperPan.getAttribute('id') === 'step-three' && !inputZip.value.length)
            ||
            (stepperPan.getAttribute('id') === 'step-three' && !gridCheck.value.length)
        ) {
            event.preventDefault()
            formEl.classList.add('was-validated')
        } else {
            if (event.detail.from < event.detail.to) {
                formValidation.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
            } else {
                formValidation.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
            }
        }
    })

    formValidationSubmit.addEventListener('click', function() {
        formEl.classList.remove('was-validated')

        if (
            (!inputAddress.value.length)
            ||
            (!inputCity.value.length)
            ||
            (!inputState.value.length)
            ||
            (!inputZip.value.length)
            ||
            (!gridCheck.value.length)
        ) {
            formEl.classList.add('was-validated')
        }
    })


    /**
     * 
     *  Validation Vertical  
     * 
     */

     var formValidationVertical = document.querySelector('.stepper-form-validation-vertical');
     var v_stepper = new Stepper(formValidationVertical, {
         animation: true
     })
     var v_formValidationVerticalNextButton = formValidationVertical.querySelectorAll('.btn-nxt');
     var v_formValidationVerticalPrevButton = formValidationVertical.querySelectorAll('.btn-prev');
     var v_formValidationVerticalSubmit = formValidationVertical.querySelector('.btn-submit');
     var v_stepperPanList = [].slice.call(formValidationVertical.querySelectorAll('.content'))
     var v_inputName = formValidationVertical.querySelector('#form-name');
     var v_inputEmail = formValidationVertical.querySelector('#emailAddress');
 
     var v_inputAddress = formValidationVertical.querySelector('#inputAddress');
     var v_inputCity = formValidationVertical.querySelector('#inputCity');
     var v_inputState = formValidationVertical.querySelector('#inputState');
     var v_inputZip = formValidationVertical.querySelector('#inputZip');
     var v_gridCheck = formValidationVertical.querySelector('#gridCheck');
 
     var v_formEl = formValidationVertical.querySelector('.bs-stepper-content form')
 
     v_formValidationVerticalNextButton.forEach(element => {
         element.addEventListener('click', function() {
             v_stepper.next();
         })
     });
 
     v_formValidationVerticalPrevButton.forEach(element => {
         element.addEventListener('click', function() {
             v_stepper.previous();
         })
     });
 
     formValidationVertical.addEventListener('show.bs-stepper', function (event) {
         v_formEl.classList.remove('was-validated')
         var v_nextStep = event.detail.indexStep
         var v_currentStep = v_nextStep
     
         if (v_currentStep > 0) {
           v_currentStep--
         }
         
         var v_stepperPan = v_stepperPanList[v_currentStep]
 
         if (
             (v_stepperPan.getAttribute('id') === 'step-one' && !v_inputName.value.length)
             ||
             (v_stepperPan.getAttribute('id') === 'step-two' && !v_inputEmail.value.length)
             ||
             (v_stepperPan.getAttribute('id') === 'step-three' && !v_inputAddress.value.length)
             ||
             (v_stepperPan.getAttribute('id') === 'step-three' && !v_inputCity.value.length)
             ||
             (v_stepperPan.getAttribute('id') === 'step-three' && !v_inputState.value.length)
             ||
             (v_stepperPan.getAttribute('id') === 'step-three' && !v_inputZip.value.length)
             ||
             (v_stepperPan.getAttribute('id') === 'step-three' && !v_gridCheck.value.length)
         ) {
             event.preventDefault()
             v_formEl.classList.add('was-validated')
         } else {
            if (event.detail.from < event.detail.to) {
                formValidationVertical.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
            } else {
                formValidationVertical.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
            }
        }
         
     })
 
     v_formValidationVerticalSubmit.addEventListener('click', function() {
         v_formEl.classList.remove('was-validated')
 
         if (
             (!v_inputAddress.value.length)
             ||
             (!v_inputCity.value.length)
             ||
             (!v_inputState.value.length)
             ||
             (!v_inputZip.value.length)
             ||
             (!v_gridCheck.value.length)
         ) {
             v_formEl.classList.add('was-validated')
         }
     })

})