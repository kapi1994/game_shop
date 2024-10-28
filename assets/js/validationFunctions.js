const inputFormValidation = (input, regInput, elementId, errorMessage, errorArray) => {
   if(!regInput.test(input)){
    errorArray.push(errorMessage)
    createValidationErrorMessage(elementId, errorMessage)
   }else {
    removeValidationErrorMessage(elementId)
   }
}

const selectFormValidation = (select, errorElementId, errorMessage, errorArray) => {
    if(select === '0' || select === 'default'){
        errorArray.push(errorMessage)
        createValidationErrorMessage(errorElementId, errorMessage)
    }else{
        removeValidationErrorMessage(errorElementId)
    }
}

const checkBoxFormValidation = (checkBoxArray, errorElementId, errorMessage, errorArray) => {
    if(checkBoxArray.length === 0){
        createValidationErrorMessage(errorElementId, errorMessage)
        errorArray.push(errorMessage)
    }else {
        removeValidationErrorMessage(errorElementId)
    }
}

const validationClasses =  ['text-danger']

const createValidationErrorMessage = (elementId, errorMessage) => {
    const element = document.querySelector(`#${elementId}`)
    element.textContent = errorMessage
    element.classList.add(...validationClasses)
}
const removeValidationErrorMessage = (elementId) => {
    const element = document.querySelector(`#${elementId}`)
    element.textContent = ''
    element.classList.remove(...validationClasses)
}

const inputDateValidation = (date, errorArray, errorMessage, errorElementId) => {
    const [emptyError, dateLower] = errorMessage
    if(date === ""){
        errorArray.push(emptyError)
        createValidationErrorMessage(errorElementId, emptyError)
    }else{
        const pickedDate = new Date(date)
        pickedDate.setHours(0,0,0,0)
        const pickedDateTimeStamp = pickedDate.getTime()
        const currentDate = new Date()
        currentDate.setHours(0,0,0,0)
        const currentDateTimeStamp = currentDate.getTime()
        if(pickedDateTimeStamp < currentDateTimeStamp){
            createValidationErrorMessage(errorElementId, dateLower)
            errorArray.push(dateLower)
        }
        else{
            removeValidationErrorMessage(errorElementId)
        }

    }
}

const inputFileFormValidation = (file, errorElementId, errorMessages,errorArray) => {
    const [emptyError, invalidTypeError, errorSize] = errorMessages
    const type = file.type
    const size = file.size
    if(parseInt(size) === 0 ){
        createValidationErrorMessage(errorElementId, emptyError)
        errorArray.push(emptyError)
    }else{
        
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg']
        if(!allowedTypes.includes(type)){
            createValidationErrorMessage(errorElementId, invalidTypeError)
            errorArray.push(invalidTypeError)
        }else if(size > 3 * 1024 * 1024){
            createValidationErrorMessage(errorElementId, errorSize)
            errorArray.push(errorSize)
        }else{
            removeValidationErrorMessage(errorElementId)
        }
    }
}