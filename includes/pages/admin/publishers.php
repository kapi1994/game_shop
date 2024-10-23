<?php 
    !isset($_SESSION['user']) || isset($_SESSION['user']) && $_SESSION['user']->role_id === 2 ? header("Location: index.php?page=errors&code=401") : "";
    $publishers = getAllPublishers();
    $pages = publisherPagination();
?>
<main>
    <div class="container">
        <div class="row mt-5" id="block">
            <div id="publisher_response_message" class="my-2"></div>
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
                        <tbody id="publishers">
                            <?php foreach($publishers as $index => $publisher):?>
                            <tr id="publisher_<?=$index?>">
                                <th scope="row"><?=$index+ 1?></th>
                                <td><?=$publisher->name?></td>
                                <td><?=date('d/m/Y H:i:s', strtotime($publisher->created_at))?></td>
                                <td><?= $publisher->updated_at !== null ? date("d-m-Y H:i:s", strtotime($publisher->updated_at)): ""?></td>
                                <td><button class="btn btn-sm btn-success btn-edit-publisher" type="button" data-id="<?=$publisher->id?>" data-index="<?=$index?>">Edit</button></td>
                                <td><button class="btn btn-sm btn-danger btn-delete-publisher" type="button" data-id="<?=$publisher->id?>" data-index="<?=$index?>" data-status="<?=$publisher->is_deleted?>">
                                    <?=$publisher->is_deleted === 0 ? "Delete" : "Activate"?>
                                </button></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="publisher-pagination">
                            <?php for($i =0; $i<$pages;$i++):?>
                                <li class="page-item page <?=$i==0 ? 'active' : ''?>"><a class="page-link pagination-publishers" href="#" data-limit="<?=$i?>"><?=$i+1?></a></li>
                            <?php endfor;?>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="#" id="publisher_form">
                    <input type="hidden" name="publisher_index" id="publisher_index">
                    <input type="hidden" name="publisher_id" id="publisher_id">
                    <div class="mb-2">
                        <label for="name" class="mb-2">Name</label>
                        <input type="text" name="name" id="name" class="form-control mb-2">
                        <div id="name_error"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-primary btn-save-publisher" id="btn-save-publisher" type="button">Save</button>
                        <button class="btn btn-sm btn-danger btn-reset-publisher" type="button" id="btn-reset-publisher">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>