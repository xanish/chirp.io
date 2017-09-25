<div class="fixed-action-btn">
    <a class="btn-floating btn-large waves-effect waves-light" data-toggle="modal" data-target="#tweetmodal">
        <i class="material-icons">add</i>
    </a>
</div>

<div class="modal fade" id="tweetmodal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title text-center pacifico">
                    <i class="icofont icofont-lg icofont-animal-woodpecker"></i>
                    <span>Compose new chirp</span>
                </h5>
            </div>
            <div class="modal-body">
                @include('partials.tweet_form')
            </div>
        </div>
    </div>
</div>
