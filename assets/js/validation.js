const formValidation = (data, entity) => {
    let errors = [];
    switch(entity){
        case 'link':
            linkFormValidation(data,errors )
            break;
        case 'platform':
            platformFormValidation(data, errors)
            break   
        case 'publisher':
            publisherFormValidation(data, errors)
            break
        case 'register_form':
           registerFormValidatioN(data, errors)
           break;
        case 'login_form':
            loginFormValidation(data, errors)   
            break;
        case 'game':
            gamesFormValidation(data, errors)
            break;
        case 'edition':
            gameEditionFormValidation(data,errors)
            break
    }
    return errors
}

const linkFormValidation = (data, errors) => {
    const {link_id, link_index, name}  = data
    
    const reName = /^[a-z]{1,}$/
    inputFormValidation(name, reName,"name_error", "Name for the link isn't good", errors)
    return errors
}

const platformFormValidation = (data, errors) => {
    const {platform_id, platform_index, name} = data
    const reName = /^[a-z]{1,}$/
    inputFormValidation(name, reName,"name_error", "Name of the platform isn't good", errors)
    return errors
}

const publisherFormValidation = (data, errors) => {
    const {publisher_id, publisher_index, name} = data
    const reName = /^[a-z]{1,}$/
    inputFormValidation(name, reName,"name_error", "Name of the publisher isn't good", errors)
    return errors
}

const registerFormValidatioN = (data, errors) => {
    const {first_name, last_name, email, password} = data
    let reFirstLastName = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/
    let reEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/
    let rePassword = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/

    inputFormValidation(first_name, reFirstLastName, "first_name_error", "First name isn't valid", errors)
    inputFormValidation(last_name, reFirstLastName, "last_name_error", "Last name isn't valid", errors)
    inputFormValidation(email, reEmail, "email_error", "Email isn't valid", errors)
    inputFormValidation(password, rePassword, "password_error", "Password isn't valid", errors)

    return errors
}

const loginFormValidation = (data, errors) => {
    const {email, password} = data
    
    let reEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/
    let rePassword = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/

    inputFormValidation(email, reEmail, "email_error", "Email isn't valid", errors)
    inputFormValidation(password, rePassword, "password_error", "Password isn't valid", errors)
    return errors

}

const gamesFormValidation = (data,errors) => {
    const {game_id, game_index, name, description, publisher,pegi_rating, published_at, genres, trailer} = data
    console.log(data)
    const reName = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/
    const reDesription = /\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/
    const reTrailer = /^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/

    inputFormValidation(name, reName, "name_error", "Name of the game isn't good", errors)
    inputFormValidation(description, reDesription, "description_error", "Description of the game isn't good", errors)
    selectFormValidation(pegi_rating, "pegi_rating_error", "Must choose a rating for the game", errors)
    selectFormValidation(publisher, "publisher_error", "Must choose a publisher", errors)
    checkBoxFormValidation(genres, "genre_error", "Choose at least one genre", errors)
    game_id === ""   || trailer.length !== 0 ?  inputFormValidation(trailer, reTrailer, "trailer_error", "Trailer for the game isn't good", errors) : ""
    selectFormValidation(pegi_rating, "pegi_rating_error", "Pleas choose raing", errors)
    inputDateValidation(published_at, errors, ["Pick a date", "Date can't be before the current date"], "published_at_error")

    return errors
}

const gameEditionFormValidation = (data, errors) => {

    const {game_id, edition_id, edition_index, platforms, price, edition, cover}  = data
    const rePrice = /^[\d]{1,5}$/
    
    inputFormValidation(price, rePrice, "price_error", "Price isn't valid", errors)
    selectFormValidation(platforms, "platform_error", "Choose platform", errors)
    selectFormValidation(edition, "edition_error", "Choose edition", errors)
    if(edition_id === ""){
        inputFileFormValidation(cover, "cover_error", ["Choose a file", "Type of the file isn't good", "File size must be lower than 3mb"], errors)
    }else if(cover.size > 0){
        inputFileFormValidation(cover, "cover_error", ["Choose a file", "Type of the file isn't good", "File size must be lower than 3mb"], errors)
    }

    return errors
    
}