<div id="tweetform">
    <form id="form" method="POST" action="/tweet" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening!"
                      maxlength="150" wrap="soft"></textarea>
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <input type="file" id="tweet_image_file" name="tweet_image" style="display: none;"
                           accept=".jpeg,.png,.jpg,.gif">
                    <button type="button" class="btn btn-default no-margin"
                            onclick="document.getElementById('tweet_image_file').click();"><i
                                class="material-icons">image</i></button>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8" id="RESPONSE_MSG">

                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 text-center padding-10">
                    <span id="count_message"></span>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <button onclick="" type="submit" class="btn btn-default pull-right no-margin" disabled="disabled" id="tweet-button">Chirp</button>
                </div>
            </div>
        </div>
    </form>
    <div class="alert alert-danger" id="ERRMSG">
        <ul>
        </ul>
    </div>
</div>
