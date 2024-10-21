<?php
// paginacija, back 
!isset($_SESSION['user']) || isset($_SESSION['user']) && $_SESSION['user']->role_id === 2 ? header("Location: index.php?page=errors&code=401") : "";
$links = getAllLinks();
$pagination = linkPagination();
?>
<main>
    <div class="container">
        <div class="row mt-5" id="block">
            <div id="link_response_message" class="my-2"></div>
            <div class="col-lg-8 mb-2 mb-lg-0">
                <div class="table-responsive-sm table-responsive-md">
                    <table class="table text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Link</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Updated at</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="links">
                            <?php foreach($links as $index => $link):?>
                            <tr id="link_<?=$index?>">
                                <th scope="row"><?=$index + 1?></th>
                                <td><?=$link->name?></td>
                                <td><?= date('d/m/Y H:i:s', strtotime($link->created_at))?></td>
                                <td><?=$link->updated_at !== null ? date("d/m/Y H:i:s", strtotime($link->updated_at)) : "-"?></td>
                                <td><button class="btn btn-sm btn-success btn-edit-link" type="button" data-id="<?=$link->id?>" data-index="<?=$index?>">Edit</button></td>
                                <td><button class="btn btn-sm btn-danger btn-delete-link" type="button" data-id="<?=$link->id?>" data-index="<?=$index?>" data-status="<?=$link->is_deleted?>">
                                    <?=$link->is_deleted === 0 ? "Delete" : "Activate"?>
                                </button></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-2">
                <nav aria-label="Page navigation example">
                        <ul class="pagination" id="link-pagination">
                            <?php for($i = 0; $i< $pagination   ; $i++):?>
                                <li class="page-item page <?=$i == 0 ? 'active' : ''?>"><a class="page-link" href="#" data-limit="<?=$i?>"><?=$i+1?></a></li>
                            <?php endfor?>
                        </ul>
                </nav>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="#" id="link_form">
                    <input type="hidden" name="link_id" id="link_id">
                    <input type="hidden" name="link_index" id="link_index">
                    <div class="mb-2">
                        <label for="name" class="mb-2">Link</label>
                        <input type="text" name="name" id="name" class="form-control mb-2">
                        <div id="name_error"></div>
                    </div>
                    <div class="d-grid gap-1">
                        <button class="btn btn-sm btn-primary btn-sm btn-save-link" id="btn-save-link" type="button">Save</button>
                        <button class="btn btn-sm btn-danger btn-sm btn-reset-link" id="btn-reset-link" type="reset">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>