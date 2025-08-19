<dialog id="review_modal" class="modal">
    <div class="modal-box bg-secondary-bg border border-border-color flex flex-col items-center">
        
        <form method="dialog" class="self-end">
            <button class="btn btn-sm btn-circle btn-ghost">✕</button>
        </form>

        <h3 class="font-bold text-lg mb-4 text-accent text-center flex items-center gap-2">
            <i class='bx bx-star'></i> Review: <?= htmlspecialchars($m['title']) ?>
        </h3>

    <form id="review-form" action="../db/rateRequests.php" method="POST" class="w-full space-y-4">
            <input type="hidden" name="movie_id" value="<?= $m['id'] ?>">

            <div class="form-control">
                <label class="label text-center">
                    <span class="label-text">Rating (1-5 stars)</span>
                </label>
                <div class="rating rating-lg justify-center">
                    <!-- <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input
                            type="radio"
                            name="rating"
                            value="<?= $i ?>"
                            class="mask mask-star-2 bg-base-200 hover:bg-warning checked:bg-warning"
                            required />
                    <?php endfor; ?> -->
                    <input type="radio" name="rating" value=1 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="1 star" />
                    <input type="radio" name="rating" value=2 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="2 star" />
                    <input type="radio" name="rating" value=3 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="3 star" />
                    <input type="radio" name="rating" value=4 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="4 star" />
                    <input type="radio" name="rating" value=5 class="mask mask-star bg-gray-200 hover:bg-yellow-500 checked:bg-warning" aria-label="5 star" />
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Your Review</span>
                </label>
                <textarea
                    name="review"
                    class="textarea textarea-bordered bg-card-bg border-border-color h-24"
                    placeholder="Share your thoughts about this movie..."
                    required></textarea>
            </div>

            <div class="modal-action justify-center gap-4">
                <button type="button" class="btn btn-outline" onclick="review_modal.close()">Cancel</button>
                <button type="submit" id="submit-review" class="btn btn-accent">Submit Review</button>
            </div>
        </form>
    </div>
</dialog>

<script>
$(function(){
    $('#review-form').on('submit', function(e){
        e.preventDefault(); 

        var $form = $(this);
        var $btn = $('#submit-review');
        $btn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json'
        })
        .done(function(res){
            console.log('Review response:', res);

            if(res && res.success){
                console.log('Review submitted successfully!');

                if(res.average){
                    $('#average-rating').text(res.average.avg.toFixed(1));
                    $('#total-reviews').text(res.average.total);
                }

                $form.find('textarea[name="review"]').val('');
                $form.find('input[name="rating"]').prop('checked', false);

                $('<p class="text-success mt-2">Thank you! Your review was submitted.</p>')
                    .appendTo($form)
                    .delay(3000)
                    .fadeOut(500, function(){ $(this).remove(); });

            } else {
                console.warn('Error submitting review:', (res && res.message) ? res.message : 'Unknown error');
            }
        })
    });
});


</script>