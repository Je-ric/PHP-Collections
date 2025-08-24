Movie App — Lists, Filters, and Recommendations

Index: Global (what everyone sees), Trending, Popular
Profile: Favorites, Rated, Recommendations

Filter/Search (index)
    - New (latest)
    - Trending / Popular (rating)
    - Genre
    - Release Year
    - Country
    - Language

More like this (viewMovie)
    - Genre (each)
    - Casts
    - Directors

Open issues / tasks
    - when adding person in add movie (there's an error) di ko pa alam reason, but in edit it's working
    - when renaming movie, change name then the image (get movieID, then call image handler)
    - add to favorites
    - fix user review display: for now star emoji; later should be icons (loop by 5)
        - determine which position is clicked (better if Tailwind component)
    - style modal

Fields (undecided)
    - average_rating (denormalize on movies for easy avg — fewer queries)
    - runtime
    - rated
        - G (General Audience) — Suitable for all ages. No offensive content. (Toy Story)
        - PG (Parental Guidance Suggested) — Some material may not be suitable for children. (Harry Potter and the Sorcerer’s Stone)
        - PG-13 (Parents Strongly Cautioned) — Some material may be inappropriate for children under 13 (violence, mild language). (Most Marvel movies — Avengers, Spider-Man)
        - R (Restricted) — Under 17 requires accompanying parent or adult. Strong language, violence, nudity. (Deadpool, Logan)
        - NC-17 (Adults Only) — No one 17 and under admitted. Explicit adult content. (Rare in mainstream; usually indie films.)

Global shelves (everyone sees)
    - Trending now (last 7 days)
        - What: Movies with the fastest recent growth.
        - Signals: unique viewers 7d, favorites 7d, reviews/ratings 7d, recency time-decay.
    - Popular this month (last 30 days)
        - What: Most consumed overall (scale, not speed).
        - Signals: unique viewers 30d, total watch time, cumulative favorites, stable (light decay).
        - Difference vs Trending: Popular = volume/scale; Trending = velocity/change.
    - Highest rated (quality gate)
        - What: Top average rating with a review-count threshold (e.g., ≥20 reviews).
        - Signals: weighted average rating with Bayesian shrinkage to avoid small-n bias.
    - New & notable
        - What: Recently added titles with above-median early engagement.
        - Signals: added_at within 14–30 days, early CTR/favorites.

Personalized shelves (because you…)
    - Use user activity: favorites, ratings, watches, search queries. Weight by recency and strength (e.g., 5★ > 3★, full watch > short play).
    - Because you like [Top Genre]
        - What: Movies from the user’s top genres, excluding already watched/favorited, sorted by personalized score.
    - Featuring [Top Actor]
        - What: Movies with actors the user often favorites/rates highly/watches.
    - From [Top Country] / In [Top Language]
        - What: Country/language affinity from viewing/favorites/ratings.
    - Directed by [Top Director]
        - What: Directors the user engages with; good precision booster.
    - Similar to your favorites
        - What: Content-based similarity (overlap in genres, cast, director, country, year range), optionally boosted by global popularity.
    - Because you rated [Movie/Genre] 4★+
        - What: Neighbor titles near items the user rated highly.
    - Continue watching (if you track progress)
        - What: Resume partially watched titles.
    - Recently added for you
        - What: New arrivals filtered by your top genres/cast/directors.

Nice-to-have personal mixes
    - Surprise me (one pick diversifying slightly from your norm)
    - Hidden gems in your genres (high rating, low popularity)
    - Award winners in your genres (if awards metadata exists)

Signals and scoring (concise)
    - User profile signals (per user):
        - Favorites count and recency by genre/country/actor/director
        - Ratings value and recency; build weighted preferences
        - Watches: completions and watch time by category
        - Searches: query → genre/cast tokens to boost interests
    - Global item signals (per movie):
        - Unique viewers/watches by day, favorites by day, ratings avg + volume, recency added
    - Example formulas:
        - TrendingScore = z1*unique_views_7d + z2*favorites_7d + z3*(avg_rating * log1p(reviews_7d)) with time-decay (e.g., 0.5^(days/7))
        - PopularScore_30d = 0.5*unique_views_30d + 0.3*watch_time_30d + 0.2*favorites_30d (light decay)
        - PersonalAffinity(user, movie) = Σ weights over matches in user top genres/countries/actors/directors + boost if similar to highly rated favorites

Freshness, quality, and UX rules
    - Freshness windows: Trending 7d, Popular 30d, New ≤30d
    - Quality gates: “Highest rated” requires min reviews (e.g., ≥20) with Bayesian shrinkage
    - Diversity:
        - Cap duplicates: a movie appears in at most 2 shelves on the home page
        - Ensure at least one non-genre shelf in first screen for exploration
    - Personalization transparency:
        - “Why you’re seeing this” tooltip like “Because you liked Action and rated Inception 5★”
    - Cold start:
        - New user: show Trending, Popular, New; ask onboarding preferences (genres, countries, languages)
        - Sparse data: infer from locale (country/language) and recent searches

Minimal data to rely on
    - User events: favorites (movie_id, ts), ratings (movie_id, value, ts), watches (movie_id, progress/time, ts), searches (query, ts)
    - Movie metadata: genres, cast (actors), directors, country, language, release_year, added_at
    - Aggregates: per-movie daily counts (views, favorites, reviews), rolling 7d/30d tallies

Recommended shelf lineup on the homepage
    - Order top rows by mix of relevance and discovery:
        1) For you (personal mix; top K blended from personal affinity + freshness)
        2) Trending now
        3) Popular this month
        4) Because you like [Top Genre]
        5) Featuring [Top Actor]
        6) From [Top Country] / In [Top Language]
        6) Highest rated (with min reviews)
        9) New & notable
        10) Similar to your favorites

Quick corrections to labels
    - Keep both “Trending now” and “Popular this month” with definitions above.
