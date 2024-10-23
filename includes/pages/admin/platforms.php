<?php 
!isset($_SESSION['user']) || isset($_SESSION['user']) && $_SESSION['user']->role_id === 2 ? header("Location: index.php?page=errors&code=401") : "";
$platforms =getAllPlatforms();
$pages = platformPagination();

?>
<main>
    <div class="container">
        <div class="row mt-5" id="block">
            <div id="platform_response_message" class="my-2"></div>
            <div class="col-lg-8 mb-2 mb-lg-0">
                <div class="table-responsive-sm table-responsive-md">
                    <table class="table text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Updated at</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="platforms">
                            <?php foreach($platforms as $index => $platform):?>
                            <tr>
                                <th scope="row"><?=$index + 1?></th>
                                <td><?=$platform->name?></td>
                                <td><?=date("d/m/Y H:i:s", strtotime($platform->created_at))?></td>
                                <td><?= $platform->updated_at !== null ? date('d/m/Y H:i:s', strtotime($platform->updated_at)) : "-"?></td>
                                <td><button class="btn btn-sm btn-success btn-edit-platform" type="button" data-id="<?=$platform->id?>" data-index="<?=$index?>">Edit</button></td>
                                <td><button class="btn btn-sm btn-danger btn-delete-platform" type="button"
                                    data-id="<?=$platform->id?>" data-status="<?=$platform->is_deleted?>" data-index="<?=$index?>"><?=$platform->is_deleted === 0 ? "Delete" : "Activate"?></button></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="platform-pagination">
                            <?php for($i = 0; $i<$pages; $i++):?>
                                <li class="page-item page <?=$i==0 ? 'active' : ''?>"><a class="page-link pagination-platforms" href="#" data-limit="<?=$i?>"><?=$i+1?></a></li>
                            <?php endfor;?>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="#" id="platform_form">
                    <input type="hidden" name="platform_id" id="platform_id">
                    <input type="hidden" name="platform_index" id="platform_index">
                    <div class="mb-2">
                        <label for="name" class="mb-2">Name</label>
                        <input type="text" name="name" id="name" class="form-control mb-2">
                        <div id="name_error"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm btn-save-platform" id="btn-save-platform" type="button">Save</button>
                        <button class="btn btn-danger btn-sm btn-reset-platform" id="btn-reset-platform" type="button">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>