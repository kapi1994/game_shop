<?php
!isset($_SESSION['user']) || isset($_SESSION['user']) && $_SESSION['user']->role_id === 2 ? header("Location: index.php?page=errors&code=401") : "";
$games = getAllGames();
$pagination = gamePagination();
$publishers = $getAvailablePublishers();
$genres = $getAllGenres();
$pegi = $getPegiRating();

?>
<main>
    <div class="container">
        <div class="row mt-5" id="block">
            <div id="game_response_message" class="my-2"></div>
            <div class="col-lg-8 mb-2 mb-lg-0">
                <div class="table-responsive-sm table-responsive-md">
                    <table class="table text-center align-middle">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Publisher Name</th>
                                <th scope="col">Pegi rating</th>
                                <th scope="col">Published at</th>
                                <th scope="col">Add new edition</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Updated at</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="games">
                            <?php foreach($games as $index => $game):?>
                            <tr id="game_<?=$index?>">
                                <th scope="row"><?=$index + 1 ?></th>
                                <td><?=$game->name?></td>
                                <td><?=$game->publisherName?></td>
                                <td><?=$game->pegiNumber?></td>
                                <td><?= date("d/m/Y", strtotime($game->published_at))?></td>
                                <td><a href="admin.php?page=editions&id=<?=$game->id?>" class="btn btn-sm btn-primary">Add</a></td>
                                <td><?= date("d/m/Y H:i:s", strtotime($game->created_at))?></td>
                                <td><?= $game->updated_at !== null ? date("d/m/Y H:i:s", strtotime($game->updated_at)) : "-"?></td>
                                <td><button class="btn btn-sm btn-success btn-edit-game" type="button" data-id="<?=$game->id?>" data-index="<?=$index?>">Edit</button></td>
                                <td><button class="btn btn-sm btn-danger btn-delete-game" type="button" data-id="<?=$game->id?>" data-index="<?=$index?>" 
                                    data-status = "<?=$game->is_deleted?>"><?=$game->is_deleted === 0 ? "Delete" : "Activate"?></button></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    <nav aria-label="Page navigation example">
                            <ul class="pagination" id="game-pagination">
                                <?php for($i = 0; $i< $pagination   ; $i++):?>
                                    <li class="page-item page <?=$i == 0 ? 'active' : ''?>"><a class="page-link pagination-games" href="#" data-limit="<?=$i?>"><?=$i+1?></a></li>
                                <?php endfor?>
                            </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-4">
                <form action="#" id="game_form" enctype="multipart/form-data">
                    <input type="hidden" name="game_id" id="game_id">
                    <input type="hidden" name="game_index" id="game_index">
                    <div class="mb-2">
                        <label for="name" class="mb-2">Name</label>
                        <input type="text" name="name" id="name" class="form-control mb-2">
                        <div id="name_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="desription" class="mb-2">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                        <div id="description_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="publisher" class="mb-2">Publisher</label>
                        <select name="publisher" id="publisher" class="form-select mb-2">
                            <option value="0">Izaberite</option>
                            <?php foreach($publishers as $publisher):?>
                                <option value="<?=$publisher->id?>"><?=$publisher->name?></option>
                            <?php endforeach;?>
                        </select>
                        <div id="publisher_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="pegi_rating" class="mb-2">Pegi rating</label>
                        <select name="pegi_rating" id="pegi_rating" class="form-control">
                            <option value="0">Choose</option>
                            <?php foreach($pegi as $p):?>
                                <option value="<?=$p->id?>"><?=$p->number?></option>
                            <?php endforeach;?>
                        </select>
                        <div id="pegi_rating_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="published_at" class="mb-2">Published at</label>
                        <input type="date" name="published_at" id="published_at" class="form-control">
                        <div id="published_at_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="genres" class="genres">Genres</label>
                        <div class="row">
                            <?php foreach($genres as $genre):?>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="<?=$genre->id?>" id="genre_<?=$genre->id?>" name="genres">
                                    <label class="form-check-label" for="genre_<?=$genre->id?>">
                                        <?=$genre->name?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                        <div id="genre_error"></div>
                    </div>
                    <div class="mb-2">
                        <label for="trailer" class="mb-2">Trailer</label>
                        <input type="text" name="trailer" id="trailer" class="form-control mb-2">
                        <div id="trailer_error"></div>
                    </div>
                    <div class="mb-2">
                        <div class="ratio ratio-16x9 d-none">
                            <iframe src="#" title="YouTube video" id="trailer_video" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-primary btn-save-game" id="btn-save-game" type="button">Save</button>
                        <button class="btn btn-sm btn-danger btn-delete-game" id="btn-reset-game" type="button">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>