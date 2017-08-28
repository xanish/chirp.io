<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Chirp!</h4>
            </div>
            <div class="modal-body">
                @include('partials.tweet_form')
                <a href="" onclick="document.getElementById('tweet_image_file').click();"><i class="material-icons"></i></a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        ><i
                            class="material-icons">image</i></button>
                <button onclick="" type="submit" class="btn btn-default" id="tweet-button">Chirp</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>