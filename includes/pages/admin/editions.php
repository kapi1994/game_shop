<?php 
    $game_id = $_GET['id'];
    $platforms = $getAvailablePlatforms();
    $editions = getAllEditions($game_id);
    $getEditionTypes = $getEditionTypes();
?>
<main>
    <div class="container">
        <div class="row mt-5" id="block">
            <div id="edition_response_message"></div>
            <div class="col-lg-8 mb-2 mb-lg-0">
                <div class="table-responsive-sm table-responsive-md">
                    <table class="table text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Platform name</th>
                                <th scope="col">Edition name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Updated at</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="editions">
                            <?php foreach($editions as $index => $edition):?>
                            <tr id="edition_<?=$index?>">
                                <th scope="row"><?=$index + 1?></th>
                                <td><?=$edition->platformName?></td>
                                <td><?=$edition->editionName?></td>
                                <td><?=$edition->price?></td>
                                <td><?= date("d/m/Y H:i:s", strtotime($edition->created_at))?></td>
                                <td><?= $edition->updated_at != null ? date("d/m/Y H:i:s", strtotime($edition->updated_at)) : "-"?></td>
                                <td><button type="button" class="btn btn-sm btn-success btn-edit-edition" data-id="<?=$edition->id?>" data-index="<?=$index?>">Edit</button></td>
                                <td><button class="btn btn-sm btn-danger btn-delete-danger btn-delete-edition"
                                     data-id="<?=$edition->id?>" data-index="<?=$index?>" data-status = "<?=$edition->is_deleted?>"><?= $edition->is_deleted  === 0 ? "Delete" : "Activate"?></button></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="#" id="edition_form" enctype="multipart/form-data">
                    <input type="hidden" name="game_id" id="game_id" value="<?=$game_id?>">
                    <input type="hidden" name="edition_id" id="edition_id">
                    <input type="hidden" name="edition_index" id="edition_index">
                    <div class="mb-2">
                        <label for="platform" class="mb-2">Platform</label>
                        <select name="platforms" id="platforms" class="form-select mb-2">
                            <option value="0">Choose</option>
                            <?php foreach($platforms as $platform):?>
                                <option value="<?=$platform->id?>"><?=$platform->name?></option>
                            <?php endforeach;?>
                        </select>
                        <div id="platform_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="price" class="mb-2">Price</label>
                        <input type="number" name="price" id="price" class="form-control mb-2">
                        <div id="price_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="edition" class="mb-2">Edition</label>
                        <select name="edition" id="edition" class="form-select mb-2">
                            <option value="0">Choose</option>
                            <?php foreach($getEditionTypes as $editionType):?>
                                <option value="<?=$editionType->id?>"><?=$editionType->name?></option>
                            <?php endforeach;?>
                        </select>
                        <div id="edition_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="cover" class="mb-2">Cover</label>
                        <input type="file" name="cover" id="cover" class="form-control mb-2">
                        <div id="cover_error"></div>
                    </div>
                    <div class="d-none" id="img_preview">
                        <img src="#" alt="#" class="img-fluid" id="cover-img">
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-primary btn-save-edition" type="button" id="btnSaveEdition">Save</button>
                        <button class="btn btn-sm btn-danger btn-reset-edition" id="btnResetEdition" type="button">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>