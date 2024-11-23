const sendGetRequest = (url, responseMessage, sendData = "") => {
    const action = url.split("/")
    const file = action[action.length - 1].split(".")[0]
    
    console.log(action)

    axios.get(url, {
        params:sendData !== "" ? sendData : ""
    }).then(data => {
        if(data.status === 200){
           if(file === "edit"){
               let entitySingular = action[1].slice(0,-1)
               let  objData = data.data
               let dataIndex = `${entitySingular}_index`
               let dataId = `${entitySingular}_id`

               objData[dataId] = objData.id
               objData[dataIndex] = parseInt(sendData.index)
               delete objData.id

               
               fillActionForm(objData)
           }else if(file === 'pagination'){
                let splitedClasses = url.split("/")
                let paginationClassSplit = splitedClasses[splitedClasses.length - 1].split(".")
                let paginationSplited = paginationClassSplit[paginationClassSplit.length - 2]
                let entityClass = splitedClasses[splitedClasses.length - 2]
                let paginationClass = `${paginationSplited}-${entityClass}`
                let whereToPlace = `${entityClass.slice(0,-1)}-pagination`
               
                
                printAllRows(data.data.data, entityClass, data.data.currentPage)
                printPagination(data.data.pages, data.data.currentPage, paginationClass, whereToPlace)
           }
        }
    })
    .catch(err => console.log(err))
        //  console.log(err.message, err.status))
}
const sendData = (url, responseMessage, sendData) => {
    const urlSplit = url.split("/")
    const action = urlSplit[urlSplit.length-1].split(".")[0]
    const entity = urlSplit[1]
    const index = `${urlSplit[1].slice(0,-1)}_index`
    console.log(sendData)
  
    axios.post(url, sendData, {
        headers:{
            'Content-Type' : 'multipart/form-data'
        }
    })
        .then(data => {
            if(data.status > 200 || data.status < 300){
                if(action === 'delete'){
                    replaceAfterDelete(data.data, sendData.index, urlSplit[1].slice(0,-1))
                }else if(action === 'store'){
                    const page = `pagination-${entity}`
                    const pagination_where_place = `${urlSplit[1].slice(0,-1)}-pagination`
                    printAllRows(data.data.data, entity, data.data.activePage)
                    delete sendData.link_active
                    clearActionForm(Object.keys(sendData), urlSplit.slice(0,1))
                    printPagination(data.data.pages, data.data.activePage, page, pagination_where_place)
                    createResponseMessage(data.stauts, data.data.message, responseMessage)
                      
                }else if(action === 'update'){
                    delete sendData.link_active
                    const index = `${urlSplit[1].slice(0,-1)}_index`
                    const objectIndex = sendData[index]
                    printRow(data.data, objectIndex, urlSplit[1], 'row')
                    clearActionForm(Object.keys(sendData), urlSplit[1].slice(0,-1))     
                    
                }else if(action === 'register'){
                    window.location.href= "index.php?page=login"
                }else if(action === 'login'){
                    data === 2 ? window.location.href = 'index.php' : window.location.href = 'admin.php'
                }
            }
        })
        .catch(err => {
            createResponseMessage(err.stauts, "Something gone wrong with the service", responseMessage)
        })

}
const fillActionForm = (data) => {

   let keys = Object.keys(data)
   let values = Object.values(data)
  
   keys.forEach((key, index) => {
       let element = document.querySelectorAll(`input[name="${key}"]`)
       if(element.length > 0){
        
            if(element.length > 1){
                let allElements = document.querySelectorAll(`input[name="${key}"]`)
                let dataValues = values[index]
                allElements.forEach(singleEl => {
                   document.querySelector(`#${singleEl.id}`).removeAttribute('checked')
                   let valueSingleEl = parseInt(singleEl.value)
                   dataValues.forEach(dataValue => {
                     let value = parseInt(dataValue)
                     valueSingleEl === value ? document.querySelector(`#${singleEl.id}`).setAttribute('checked', true) : ''
                   })
                   
                })
            }else{
                console.log(key)
                 if(key === 'cover'){
                    let image = document.querySelector('#img_preview')
                    image.classList.remove('d-none')
                    let cover = document.querySelector('#cover-img')
                    cover.src = `assets/img/normal/${values[index]}`
                    
                }else if(key === 'trailer'){
                    document.querySelector(`#${key}`).value = ''
                    let player = document.querySelector('#trailer_video')
                    player.src = `https://www.youtube.com/embed/${values[index]}`
                    player.parentNode.classList.remove('d-none')
                }else {
                    document.querySelector(`#${key}`).value = values[index]
                }
            
            }
       }
   })
}
const clearActionForm = (objectData, entity) => {
    objectData.forEach(objData => {
        if(document.querySelectorAll(`input[name='${objData}']`).length > 1){
            let elements = document.querySelectorAll(`input[name='${objData}']`)
            elements.forEach(element => {
                let id = element.id
                let elementForm = document.querySelector(`#${id}`)
                elementForm.checked = false
            })
            
        }else {
            
            let element = document.querySelector(`#${objData}`)
            element.value = ''
            element.removeAttribute('value')
            if(entity === 'game'){
                const player = document.querySelector('#trailer_video')
                player.src = '#'
                player.parentNode.classList.add('d-none')
            }
        }
    })
}
const createResponseMessage = (statusCode, message, whereToPlace) => {
    const color = statusCode > 400 ? 'danger' : 'success'
    const messageContent = `
        <div class="alert alert-${color} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `
    document.querySelector(`#${whereToPlace}`).innerHTML = messageContent
}
const replaceAfterDelete = (data, index, entity) => {
    const cls = `btn-delete-${entity}`
    const elements = document.querySelectorAll(`.${cls}`) 
    elements.forEach((element) => {
        const el_id = parseInt(element.dataset.id)
        if(parseInt(data.id) === el_id){
            element.dataset.status = data.is_deleted
            element.textContent = parseInt(data.is_deleted) === 0 ? "Delete" : "Activate" 
        }
    })
}
const printAllRows = (data, entity, limit = 0) => {
    let content = '', index = (parseInt(limit) * 5) + 1

    data.forEach(dataRow => {
        content+= printRow(dataRow, index, entity)
        index++
        
    })
    document.querySelector(`#${entity}`).innerHTML = content
}
const printRow = (data, index, entity = '', operation = '') => {
    console.log(entity)
    let content = ` 
    <tr id="link_${index}">
        <th scope="row">${index}</th>`

    if(entity.slice(0,-1) === 'game'){
      
        content+=`
        <td>${data.publisherName}</td>     
       
        <td>${data.pegiName}</td>
        <td>${formatDate(data.published_at)}</td>
        <td><a href="admin.php?page=editions&id=${data.id}" class="btn btn-sm btn-primary">Add</a></td>
        `
    }  
    else if(entity.slice(0,-1) === 'edition'){
       content+=`<td>${data.platformName}</td>
        <td>${data.editionName}</td>
       `
    }else{
       content+=` <td>${data.name}</td>`
    }
    content+=`<td>${formatDate(data.created_at)}</td>
        <td>${data.updated_at !== null ? formatDate(data.updated_at) : "-"}</td>
        <td><button class="btn btn-sm btn-success btn-edit-${entity.slice(0,-1)}" type="button" data-id="${data.id}" data-index="${index}">Edit</button></td>
        <td><button class="btn btn-sm btn-danger btn-delete-${entity.slice(0,-1)}" type="button" data-id="${data.id}" data-index="${index}" data-status="${data.is_deleted}">
            ${parseInt(data.is_deleted) === 0 ? "Delete" : "Activate"}
        </button></td>
    </tr>`
    if(operation === "row"){
      const whereToPlace = `${entity.slice(0,-1)}_${index}`
      document.querySelector(`#${whereToPlace}`).innerHTML = content
    }

    return content

}
const printPagination = (links, currentPage, cls, whereToPlace) => {
    let content = ''
    for(let i = 0; i<links; i++){
        let activeClass = parseInt(currentPage) === parseInt(i) ? "active" : ""
        content += `<li class="page-item page ${activeClass}"><a class="page-link ${cls}" href="#" data-limit="${i}">${i+1}</a></li>`
    }
    document.querySelector(`#${whereToPlace}`).innerHTML = content
   
}
const  formatDate = (dateD) =>{
    const date = dateD.split(" ")[0].split("-")
    const time = dateD.split(" ")[1] != null ? dateD.split(" ")[1] :''  
   
    return date[2] + '/' + date[1] + '/' + date[0] + " " + time
}
const getActivePagiantionClass = (links) => {
   let linkIndex = ''
   links.forEach((link, index) => {
     if(link.classList.contains('active'))
        linkIndex = index
     
   })
   return linkIndex
}
const getValuesFromCheckBoxesArray = (checkboxArray, valuesArray) => {
    checkboxArray.forEach(check => {
        valuesArray.push(check.value)
    })
    return valuesArray
}
