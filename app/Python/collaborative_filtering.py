import sys
import pandas as pd
import numpy as np
import json
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.metrics import pairwise_distances


user = int(sys.argv[1])


articles = pd.read_csv(
    "C:/wamp64/www/eGame/app/Python/articles.csv", encoding="Latin1")
Ratings = pd.read_csv("C:/wamp64/www/eGame/app/Python/ratings.csv")


articles = articles.rename(columns={"id": "article_id"})


Mean = Ratings.groupby(by="user_id", as_index=False)['score'].mean()
Rating_avg = pd.merge(Ratings, Mean, on='user_id')
Rating_avg['adg_score'] = Rating_avg['score_x'] - Rating_avg['score_y']

check = pd.pivot_table(Rating_avg, values='score_x',
                       index='user_id', columns='article_id')


final = pd.pivot_table(Rating_avg, values='adg_score',
                       index='user_id', columns='article_id')


final_article = final.fillna(final.mean(axis=0))


final_user = final.apply(lambda row: row.fillna(row.mean()), axis=1)


b = cosine_similarity(final_user)
np.fill_diagonal(b, 0)
similarity_with_user = pd.DataFrame(b, index=final_user.index)
similarity_with_user.columns = final_user.index


cosine = cosine_similarity(final_article)
np.fill_diagonal(cosine, 0)
similarity_with_article = pd.DataFrame(cosine, index=final_article.index)
similarity_with_article.columns = final_user.index


def find_n_neighbours(df, n):
    order = np.argsort(df.values, axis=1)[:, :n]
    df = df.apply(lambda x: pd.Series(x.sort_values(ascending=False)
                                      .iloc[:n].index,
                                      index=['top{}'.format(i) for i in range(1, n+1)]), axis=1)
    return df


sim_user_6_u = find_n_neighbours(similarity_with_user, 6)


sim_user_6_m = find_n_neighbours(similarity_with_article, 6)


Rating_avg = Rating_avg.astype({"article_id": str})
Article_user = Rating_avg.groupby(
    by='user_id')['article_id'].apply(lambda x: ','.join(x))


def get_predicted_articles(user):
    Article_seen_by_user = check.columns[check[check.index == user].notna(
    ).any()].tolist()
    a = sim_user_6_m[sim_user_6_m.index == user].values
    b = a.squeeze().tolist()
    d = Article_user[Article_user.index.isin(b)]
    l = ','.join(d.values)
    Article_seen_by_similar_users = l.split(',')
    Articles_under_consideration = list(
        set(Article_seen_by_similar_users)-set(list(map(str, Article_seen_by_user))))
    Articles_under_consideration = list(map(int, Articles_under_consideration))
    score = []
    for item in Articles_under_consideration:
        c = final_article.loc[:, item]
        d = c[c.index.isin(b)]
        f = d[d.notnull()]
        avg_user = Mean.loc[Mean['user_id'] == user, 'score'].values[0]
        index = f.index.values.squeeze().tolist()
        corr = similarity_with_article.loc[user, index]
        fin = pd.concat([f, corr], axis=1)
        fin.columns = ['adg_score', 'correlation']
        fin['score'] = fin.apply(
            lambda x: x['adg_score'] * x['correlation'], axis=1)
        nume = fin['score'].sum()
        deno = fin['correlation'].sum()
        final_score = avg_user + (nume/deno)
        score.append(final_score)
    data = pd.DataFrame(
        {'article_id': Articles_under_consideration, 'score': score})
    top_6_recommendation = data.sort_values(
        by='score', ascending=False).head(6)
    Article_ID = top_6_recommendation.merge(
        articles, how='inner', on='article_id')
    Article_IDs = json.dumps(Article_ID.article_id.values.tolist())
    return Article_IDs


predicted_articles = get_predicted_articles(user)
print(predicted_articles)
