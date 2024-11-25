
const block = document.querySelector('#block')
block ? block.addEventListener('click', (e) => {
    
    let element = e.target
    let classArray = element.classList
    let lastClass = classArray[classArray.length - 1]
    let splitedLastClass = lastClass.split('-')

    let entity = splitedLastClass[splitedLastClass.length - 1]
    let action = splitedLastClass[1]

    let url = `models/${entity}s/${action}.php`
    let responseMessage = `${entity}_response_message`
    

    if(element.matches("table tbody td button.btn-success")){
        sendGetRequest(url, responseMessage, element.dataset)
    }else if(element.matches("table tbody td button.btn-danger")){
        sendData(url, responseMessage, element.dataset)
    }else if(element.matches("form button.btn-primary")){
       let index =  getActivePagiantionClass(document.querySelectorAll('.pagination li'))
       action = document.querySelector(`#${entity}_id`).value === "" ? "store" : "update"
       url = `models/${entity}s/${action}.php`
        
       const formData = new FormData(document.querySelector(`#${entity}_form`))
       formData.append("link_active", index)
       if(entity === 'game'){
         const gernesArray = []
         const genres = document.querySelectorAll('input[name="genres"]:checked')
         formData.append('genres', getValuesFromCheckBoxesArray(genres, gernesArray))
       }
       else if(entity === 'edition'){
        const old_img_cover = document.querySelector('#cover-img').src.split('/')
        const name_old_img_cover = old_img_cover[old_img_cover.length - 1]
        formData.append('old_img_cover',  name_old_img_cover)
       }
      
       const data = Object.fromEntries(formData)
       formValidation(data, entity).length === 0 ? sendData(url, responseMessage, data) : ""
    }else if(element.matches("form button.btn-danger")){
       clearActionForm(Object.keys(Object.fromEntries(new FormData(document.querySelector(`#${entity}_form`)))))
    }else if(element.matches("ul.pagination li a")){
        entity = lastClass.split("-")[1]
        action = lastClass.split("-")[0]
        url = `models/${entity}/${action}.php`
        sendGetRequest(url, responseMessage, element.dataset)
    }
   
}) : ""