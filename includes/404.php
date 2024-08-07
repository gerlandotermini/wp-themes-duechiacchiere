<?php
if ( is_search() ) {
    // If we are here after searching, it means that no posts matched the search string
    // Let's show a list of random posts
    $related_posts = get_posts( "numberposts=5&orderby=rand" );
}
else {
    // Use the URL as keywords, maybe we'll find something there
    $uri_404 = str_replace( '-', '+', str_replace( '/', '', $_SERVER[ 'REQUEST_URI' ] ) );
    $search_related = new WP_Query( [
        'post_type' => 'post',
        'posts_per_page' => 5,
        'orderby' => 'post_date',
        's' => $uri_404
    ] );

    if ( !empty( $search_related->posts ) ) {
        $related_posts = $search_related->posts;
    }
    else {
        $related_posts = get_posts( "numberposts=5&orderby=rand" );
    }
}
?>

<article>
    <header><h1><?= is_search() ? 'Nessun risultato per ' . $search_keywords : 'Contenuto non trovato' ?></h1></header>
    <div class="entry">
        <p>
            "C'era una volta una post...", diranno subito i piccoli lettori di questo blog. 
            Era un post grazioso, rifinito in tutti i particolari, davvero adorabile. 
            Poi deve essere successo qualcosa di terribile, qualcosa che non oso neppure immaginare.
            Un lampo di luce, un suono assordante, l'odore acre del codice <abbr title="hypertext markup language">HTML</abbr>
            che bruciacchia, e quel post non c'era pi&ugrave;, sparito per sempre nel limbo dei
            post che non sono.</p>
            
            <p>Di chi sia la colpa &egrave; difficile a dirsi. Google? Yahoo? I tizi del provider che ospita
            questo sito? L'autore di duechiacchiere.it? Si, deve essere stato lui, quel rimbambito ha pensato
            che il post stesse solo occupando spazio inutile e deve averlo freddato con un colpo secco. Ma
            come si permette, quell'idiota?</p>
            
            <p>E cos&igrave; eccoci qui. Che si fa adesso? Beh, personalmente ti consiglierei di dare
            un'occhiata alla navigazione in alto, chiss&agrave; che non noti qualche indicazione
            utile a ritrovare la retta via. Se proprio non riesci a scovare quello che stavi cercando, ti consiglio di
            contattare l'autore del blog tramite <a href="/contatto">l'apposito modulo</a>.
            Nel frattempo, ti propongo qui di seguito altri post che potrebbero stuzzicare la tua curiosit&agrave;.
        </p>
    </div>
</article>

<?php foreach ( $related_posts as $a_post ): ?>
<article>
    <header><h2><a href="<?= get_the_permalink( $a_post->ID ) ?>"><?= get_the_title( $a_post->ID ) ?></a></h2></header>
    <div class="entry">
        <?= apply_filters( 'the_content', get_the_content( 'Leggi il resto<span class="visually-hidden"> di ' . get_the_title( $a_post->ID ) . '</span>', false, $a_post->ID ) ); ?>
    </div>
</article>
<?php endforeach; wp_reset_postdata(); ?>