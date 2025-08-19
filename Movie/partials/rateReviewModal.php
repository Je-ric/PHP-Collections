<dialog id="review_modal" class="modal">
    <div class="modal-box bg-secondary-bg border border-border-color">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4 text-accent">
            <i class='bx bx-star'></i> Review: <?= htmlspecialchars($m['title']) ?>
        </h3>

        <form action="../db/rateRequests.php" method="POST" class="space-y-4">
            <input type="hidden" name="movie_id" value="<?= $m['id'] ?>">

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Rating (1-5 stars)</span>
                </label>
                <div class="rating rating-lg">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="rating" value="<?= $i ?>" class="mask mask-star-2 bg-warning" required />
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Your Review</span>
                </label>
                <textarea name="review" class="textarea textarea-bordered bg-card-bg border-border-color h-24"
                    placeholder="Share your thoughts about this movie..." required></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-outline" onclick="review_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-accent">Submit Review</button>
            </div>
        </form>
    </div>
</dialog>