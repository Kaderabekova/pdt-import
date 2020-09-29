import datetime

from django.shortcuts import render
from rest_framework import generics
from rest_framework.response import Response
from django.contrib.gis.geos import Point

from .serializers import ImportSerializer
# Create your views here.

from django.http import HttpResponse
import json_lines

from .models import Account, Country, Tweet, TweetMention, TweetHashtag, Hashtag


def create_account(item):
    account, _created = Account.objects.get_or_create(
        id=item['id'],
        defaults={
            'name': item['name'],
            'screen_name': item['screen_name'],
            'description': item['description'] if 'description' in item else None,
            'followers_count': item['followers_count'] if 'followers_count' in item else None,
            'friends_count': item['friends_count'] if 'friends_count' in item else None,
            'statuses_count': item['statuses_count'] if 'statuses_count' in item else None
        }
    )
    # look for changes in account
    change = False

    if account.screen_name != item['screen_name']:
        account.screen_name = item['screen_name']
        change = True
    if account.name != item['name']:
        account.name = item['name']
        change = True
    if 'description' in item:
        if account.description is None and item['description'] is not None or account.description != item['description']:
            account.description = item['description']
            change = True
    if 'followers_count' in item:
        if account.followers_count is None and item['followers_count'] is not None or account.followers_count != item['followers_count']:
            account.followers_count = item['followers_count']
            change = True
    if 'friends_count' in item:
        if account.friends_count is None and item['friends_count'] is not None or account.friends_count != item['friends_count']:
            account.friends_count = item['friends_count']
            change = True
    if 'statuses_count' in item:
        if account.statuses_count is None and item['statuses_count'] is not None or account.statuses_count != item['statuses_count']:
            account.statuses_count = item['statuses_count']
            change = True
    if change:
        account.save()
    return account


def create_tweet(item, account):
    happened_at = datetime.datetime.strptime(item['created_at'], '%a %b %d %H:%M:%S %z %Y')
    tweet, _created = Tweet.objects.get_or_create(id=item['id'],
                                        defaults={
                                            'content':  item['full_text'],
                                            'location': Point(item['coordinates']['coordinates'][1], item['coordinates']['coordinates'][0]) if item['coordinates'] else None,
                                            'retweet_count': item['retweet_count'] if 'retweet_count' in item else None,
                                            'favorite_count': item['favorite_count'] if 'favorite_count' in item else None,
                                            'happened_at': datetime.datetime.strftime(happened_at, '%Y-%m-%d %H:%M:%S.%u'),
                                            'author': account
                                        })
    return tweet


def create_country(item):
    country, _created = Country.objects.get_or_create(code=item['country_code'], name=item['country'])
    return country


def create_hashtag(item):
    tag, _created = Hashtag.objects.get_or_create(value=item['text'])
    return tag


def create_tweet_mention(tweet, account):
    tweet_mention, _created = TweetMention.objects.get_or_create(tweet=tweet, account=account)
    return tweet_mention


def create_tweet_hashtag(tweet, hashtag):
    tweet_hashtag, _created = TweetHashtag.objects.get_or_create(tweet=tweet, hashtag=hashtag)
    return tweet_hashtag


class ImportView(generics.CreateAPIView):
    serializer_class = ImportSerializer

    def create(self, request):
        with json_lines.open('D:\\Plocha\\FIIT\\Ing\\3_semester\\PDT\\PDT-20200921T162139Z-001\\'+request.data['file_name']) as json_data:

            for item in json_data:
                # read account
                account = create_account(item['user'])
                # read tweet
                tweet = create_tweet(item, account)

                # read country
                if item['place']:
                    country = create_country(item['place'])
                    tweet.country = country
                    tweet.save()

                # read tweet_mentions
                if 'user_mentions' in item['entities']:
                    for user in item['entities']['user_mentions']:
                        # TODO: test, if exists
                        # read user
                        mention = create_account(user)

                        # create tweet_mention relationship
                        tweet_mention = create_tweet_mention(tweet, mention)

                # read tweet hashtag
                if 'hashtags' in item['entities']:
                    for hashtag in item['entities']['hashtags']:
                        htag = create_hashtag(hashtag)
                        tweet_hashtag = create_tweet_hashtag(tweet, htag)

                if 'retweeted_status' in item:
                    # read account
                    r_account = create_account(item['retweeted_status']['user'])

                    r_tweet = create_tweet(item['retweeted_status'], r_account)

                    tweet.parent_id = r_tweet.id
                    tweet.save()

                    # read country
                    if item['retweeted_status']['place']:
                        r_country = create_country(item['retweeted_status']['place'])
                        r_tweet.country_id = r_country
                        r_tweet.save()

                    # read tweet_mentions
                    if 'user_mentions' in item['retweeted_status']['entities']:
                        for user in item['retweeted_status']['entities']['user_mentions']:
                            # read user
                            r_mention = create_account(user)
                            r_tweet_mention = create_tweet_mention(r_tweet, r_mention)

                    # read tweet hashtag
                    if 'hashtags' in item['retweeted_status']['entities']:
                        for hashtag in item['retweeted_status']['entities']['hashtags']:
                            r_tag = create_hashtag(hashtag)
                            r_tweet_hashtag = create_tweet_hashtag(r_tweet, r_tag)

        return Response({
            'status': 200,
            'message': 'File was successfully imported.'
            }
        )

