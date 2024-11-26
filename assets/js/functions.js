const sendGetRequest = (url, responseMessage, sendData = "") => {
    const action = url.split("/")
    const file = action[action.length - 1].split(".")[0]
    const entity = action[1]

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

               
               fillActionForm(objData, entity)
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
    .catch(error => {
        createResponseMessage(error.status, "Something gone wrong with the service! Try agian later.", responseMessage)
    })
        //  console.log(err.message, err.status))
}
const sendData = (url, responseMessage, sendData) => {
    const urlSplit = url.split("/")
    const action = urlSplit[urlSplit.length-1].split(".")[0]
    const entity = urlSplit[1]
  
    axios.post(url, sendData, {
        headers:{
            'Content-Type' : 'multipart/form-data'
        }
    })
        .then(data => {
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
                    printRow(data.data, Number(objectIndex) + 1, urlSplit[1], 'row')
                    clearActionForm(Object.keys(sendData), urlSplit[1].slice(0,-1))     
                    
                }else if(action === 'register'){
                    window.location.href= "index.php?page=login"
                }else if(action === 'login'){
                    data === 2 ? window.location.href = 'index.php' : window.location.href = 'admin.php'
                }
            
        })
        .catch(err => {
            createResponseMessage(err.status, err.response.data, responseMessage)
        })

}
const fillActionForm = (data, entity) => {

   let keys = Object.keys(data)
   let values = Object.values(data)
   keys.forEach((key, index) => {
     let elements = document.getElementsByName(`${key}`)
     const valuesData = values[index]
     if(elements.length > 1){
        elements.forEach(element => {
            document.querySelector(`#${element.id}`).removeAttribute('checked')
            valuesData.includes(Number(element.value)) ? document.querySelector(`#${element.id}`).setAttribute('checked', true) : ''
        })
     }else{
         if(key === 'cover'){
            const img_cover = document.querySelector(`#${key}-img`)
            img_cover.src = `assets/img/normal/${values[index]}`
            img_cover.parentNode.classList.remove('d-none')
         }else if(key === 'trailer'){
            console.log(key)
            const trailer = document.querySelector(`#${key}`)
            trailer.value = `https://www.youtube.com/watch?v=${values[index]}`

            const trailer_player = document.querySelector(`#${key}_video`)
            trailer_player.src = `https://www.youtube.com/embed/${values[index]}`
            trailer_player.parentNode.classList.remove("d-none")
           
         }else{
            document.querySelector(`#${key}`).value = values[index]
            console.log(document.querySelector(`#${key}`))
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
          if(entity === "edition"){
           const img_cover = document.querySelector('#cover-img')
           const img_preview = document.querySelector('#img_preview')
           img_cover.src = '#'
           img_preview.classList.add('d-none')
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
        <td>${data.price}</td>
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
      const whereToPlace = entity.slice(0,-1)+"_"+(index-1)
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
