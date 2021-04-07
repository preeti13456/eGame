<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

use Auth;
use App\Order;
use App\Rating;
use App\Article;
use App\ShoppingCart;
use App\InShoppingCart;
use App\OrderedArticle;
use App\RecommendationsSystemWeigth;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'id',
        'name',
        'price',
        'quantity',
        'release_date',
        'players_num',
        'gender',
        'platform',
        'description',
        'assessment',
    ];

    //Query Scopes:
    public function scopeLatest($query)
    {
        return $query->orderBy('id', 'asc');
    }

    public function scopeName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
    }

    public function scopePlatform($query, $platform)
    {
        if ($platform) {
            return $query->where('platform', 'like', '%' . $platform . '%');
        }
    }

    public function scopeGender($query, $gender)
    {
        if ($gender) {
            return $query->where('gender', 'like', '%' . $gender . '%');
        }
    }

    public function scopePrice($query, $price)
    {
        switch ($price) {
            case 'Minus 10€':
                return $query->where('price', '<', 10);
                break;
            case '10€ - 20€':
                return $query->whereBetween('price', [10, 20]);
                break;
            case '20€ - 30€':
                return $query->whereBetween('price', [20, 30]);
                break;
            case '30€ - 40€':
                return $query->whereBetween('price', [30, 40]);
                break;
            case '40€ - 50€':
                return $query->whereBetween('price', [40, 50]);
                break;
            case '50€ - 60€':
                return $query->whereBetween('price', [50, 60]);
                break;
            case 'More 60€':
                return $query->where('price', '>', 60);
                break;
            default:
                return 0;
                break;
        }
    }

    public function scopeReleaseDate($query, $releaseDate)
    {
        if ($releaseDate) {
            return $query->where(
                'release_date',
                'like',
                '%' . $releaseDate . '%'
            );
        }
    }

    public function paypalItem()
    {
        $shopping_cart_id = ShoppingCart::where('user_id', Auth::user()->id)
            ->get()
            ->last()->id;
        $in_shopping_carts = InShoppingCart::get()->where(
            'shopping_cart_id',
            $shopping_cart_id
        );

        $in_shopping_cart_article_quantity = $in_shopping_carts
            ->where('article_id', $this->id)
            ->first()->quantity;

        return \PaypalPayment::item()
            ->setName($this->name)
            ->setDescription($this->description)
            ->setCurrency('EUR')
            ->setQuantity($in_shopping_cart_article_quantity)
            ->setPrice($this->price / 100);
    }

    public static function generateArticlesMatrix()
    {
        $articlesDB = Article::all();

        $articlesAsociativeMatrix = [];

        for ($i = 0; $i < sizeof($articlesDB); $i++) {
            $articlesAsociativeMatrix[$i] = [
                'id' => $articlesDB[$i]->id,
                'name' => $articlesDB[$i]->name,
                'price' => $articlesDB[$i]->price,
                'gender' => $articlesDB[$i]->gender,
                'platform' => $articlesDB[$i]->platform,
                'quantity' => $articlesDB[$i]->quantity,
                'extension' => $articlesDB[$i]->extension,
                'assessment' => $articlesDB[$i]->assessment,
                'players_num' => $articlesDB[$i]->players_num,
                'release_date' => $articlesDB[$i]->release_date,
            ];
        }

        return $articlesAsociativeMatrix;
    }

    public static function filteredBySimilarArticles($id)
    {
        $articlesMatrix = Article::generateArticlesMatrix();
        $articleSimilarity = new ContentBasedFiltering($articlesMatrix);

        $articleSimilarity->setPriceWeight(
            RecommendationsSystemWeigth::first()->price
        );
        $articleSimilarity->setGenderWeight(
            RecommendationsSystemWeigth::first()->gender
        );
        $articleSimilarity->setPlatformWeight(
            RecommendationsSystemWeigth::first()->platform
        );

        $similarityMatrix = $articleSimilarity->calculateSimilarityMatrix();

        $articlesSortedBySimilarity = $articleSimilarity->getArticlesSortedBySimilarity(
            $id,
            $similarityMatrix
        );

        return $articlesSortedBySimilarity;
    }

    public static function filteredByUserPurchases()
    {
        $ordersByUser = Order::where('user_id', Auth::user()->id)->get();
        $articles = [];
        foreach ($ordersByUser as $orderByUser) {
            foreach (
                OrderedArticle::where('order_id', $orderByUser->id)->get()
                as $article
            ) {
                $articles[] = $article->article_id;
            }
        }

        $articlesIdOrderedByUser = array_unique($articles);
        $articlesOrderedByUser = Article::wherein(
            'id',
            $articlesIdOrderedByUser
        )->get();
        $articlesDB = Article::all();

        $articlesMatrix = Article::generateArticlesMatrix();

        $articleSimilarity = new ContentBasedFiltering($articlesMatrix);

        $articleSimilarity->setPriceWeight(
            RecommendationsSystemWeigth::first()->price
        );
        $articleSimilarity->setGenderWeight(
            RecommendationsSystemWeigth::first()->gender
        );
        $articleSimilarity->setPlatformWeight(
            RecommendationsSystemWeigth::first()->platform
        );

        $similarityMatrix = $articleSimilarity->calculateSimilarityMatrix();

        $recommendedArticlesByPurchases = [];

        foreach ($articlesIdOrderedByUser as $articleIdOrderedByUser) {
            $recommendedArticlesByPurchases = $articleSimilarity->getArticlesSortedBySimilarity(
                $articleIdOrderedByUser,
                $similarityMatrix
            );
        }

        return $recommendedArticlesByPurchases;
    }

    public static function bestSellers()
    {
        $articles = Article::all();

        for ($i = 0; $i < sizeof($articles); $i++) {
            $bestSellers[$i] = [
                'id' => $articles[$i]->id,
                'name' => $articles[$i]->name,
                'price' => $articles[$i]->price,
                'gender' => $articles[$i]->gender,
                'platform' => $articles[$i]->platform,
                'quantity' => $articles[$i]->quantity,
                'extension' => $articles[$i]->extension,
                'assessment' => $articles[$i]->assessment,
                'players_num' => $articles[$i]->players_num,
                'release_date' => $articles[$i]->release_date,
                'purchasesNum' => OrderedArticle::orderedArticleCount(
                    $articles[$i]->id
                ),
            ];
        }

        $bestSellers = collect($bestSellers)
            ->sortBy('purchasesNum')
            ->reverse()
            ->toArray();

        return $bestSellers;
    }

    public static function bestRated()
    {
        return Article::orderBy('assessment', 'desc')
            ->limit(6)
            ->get();
    }
}
?>
