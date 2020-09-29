--
-- PostgreSQL database dump
--

-- Dumped from database version 11.2 (Debian 11.2-1.pgdg90+1)
-- Dumped by pg_dump version 11.2 (Debian 11.2-1.pgdg90+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: tiger; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger;


ALTER SCHEMA tiger OWNER TO postgres;

--
-- Name: tiger_data; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger_data;


ALTER SCHEMA tiger_data OWNER TO postgres;

--
-- Name: topology; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA topology;


ALTER SCHEMA topology OWNER TO postgres;

--
-- Name: SCHEMA topology; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA topology IS 'PostGIS Topology schema';


--
-- Name: fuzzystrmatch; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS fuzzystrmatch WITH SCHEMA public;


--
-- Name: EXTENSION fuzzystrmatch; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION fuzzystrmatch IS 'determine similarities and distance between strings';


--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


--
-- Name: postgis_tiger_geocoder; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_tiger_geocoder WITH SCHEMA tiger;


--
-- Name: EXTENSION postgis_tiger_geocoder; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_tiger_geocoder IS 'PostGIS tiger geocoder and reverse geocoder';


--
-- Name: postgis_topology; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_topology WITH SCHEMA topology;


--
-- Name: EXTENSION postgis_topology; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_topology IS 'PostGIS topology spatial types and functions';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: accounts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.accounts (
    id bigint NOT NULL,
    screen_name character varying(200),
    name character varying(200),
    description text,
    followers_count integer,
    friends_count integer,
    statuses_count integer
);


ALTER TABLE public.accounts OWNER TO postgres;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.countries (
    id integer NOT NULL,
    code character varying(2),
    name character varying(200)
);


ALTER TABLE public.countries OWNER TO postgres;

--
-- Name: hashtags; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hashtags (
    id integer NOT NULL,
    value text
);


ALTER TABLE public.hashtags OWNER TO postgres;

--
-- Name: tweet_hashtags; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tweet_hashtags (
    id integer NOT NULL,
    hashtag_id integer,
    tweet_id character varying(20)
);


ALTER TABLE public.tweet_hashtags OWNER TO postgres;

--
-- Name: tweet_mentions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tweet_mentions (
    id integer NOT NULL,
    account_id bigint,
    tweet_id character varying(20)
);


ALTER TABLE public.tweet_mentions OWNER TO postgres;

--
-- Name: tweets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tweets (
    id character varying(20) NOT NULL,
    content text,
    location public.geometry(Point,4326),
    retweet_count integer,
    favorite_count integer,
    happened_at timestamp with time zone,
    author_id bigint,
    country_id integer,
    parent_id character varying(20)
);


ALTER TABLE public.tweets OWNER TO postgres;

--
-- Data for Name: accounts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.accounts (id, screen_name, name, description, followers_count, friends_count, statuses_count) FROM stdin;
1161447195159203841	Maggie62699325	Maggie	All my grandkids are beautiful, WWG1WGA, Q follower, MAGA, President Trump is the Best. Jesus is my precious King.	105	503	4689
84520007	BetoNapoleon	Don Napoleon	Chivas, Dallas Cowboys	169	751	6120
21103888	JohnWesleyShipp	John Wesley Shipp	actor/musician Facebook: johnwesleyshipp7@facebook.com  Instagram: johnwesleyshippjr	94598	803	57572
2409412165	maloneyinsight	Ryan	Proud father of two, scientist, fan and part-time critic of political theater, and severe attention deficit diso...	15	152	2902
154647036	magia_19	A3		227	319	4541
795429282298245120	Melmaxjoe	Joe Max	Progressive to the core! #RESISTANCE No bullshit. VOTE BLUE 2020! TAKE BACK  WH,SENATE& HOUSE!HAPPILY MARRIED NO DM's NO LISTS! I ALWAYS FOLLOW BACK (IF LEGIT)	3164	4929	18732
1244464921250631681	naivelynho	na⁸ IN生	harta, tahta, ollata	592	629	5365
1109124407056912384	miscgurl	Kayla Ann	Be the light you wish someone would have been for you 💫 #FIU21	95	140	1627
4045134814	Fvxkreality_	ӄǟʍɨ 🌺	No jagbajantis.	631	1827	201745
1196088434	bowman_beverly	Bowman	wash your damn hands and wear a mask. It is not a political statement, it’s about keeping everyone safe.	205	62	78237
475217541	dvorakfilms	MarieAnna Dvorak	Film Director; Psychologist; Victim of totalitarianism; President Trump 2020; MAGA-KAG; "Where We Go One We Go All."	3729	4950	27869
1264447351986376705	RajeshD77951974	Rajesh Dwivedi		2	2	283
1954445833	simao10costa	10Costa	instagram: simao10costa	325	316	5826
2525114809	Heathen49	주변인, 섬🌴	남쪽 섬에서 시시하고 소소하게 그냥 그렇게 살 수 있을까?	3526	709	221435
199071586	realmarkleach	Mark Leach	I pastor Indian Heights Community Church. I am Dean of Men at UBC. I’m married to the love of my life, Evanne. I’m having the time of my life serving Jesus!	362	352	2354
798932623452151813	ReaganPresident	Lisa Rogers		303	666	49827
115308684	federicoalves	Federico Alves	Economist from Vzla's UCV. \nHarvard Ext. School. \nLive in #Fl, #USA.\nMARRIED.  Anti-Bitcoinist. \nAnti-communist.  REPUBLICAN.\n#Tampa, Fl. Radio host.	72082	27826	1345979
951273210154291200	andrew_bier	Andrew Bier	#bitcoin	10	155	222
46070798	SingleJ	Justin Daniel		48	214	810
1217689719917559811	BaynmonkhBaynaa	Baynaa Baynmonkh	😊😊😊Сонирхогч🙂🙂🙂	228	500	91
3146852778	GrandMaRia61	Maria McGu	NO DM's! Mom/grandma & Irish Wolfhound mom and aficionado.  Enjoying the blessings of this life ⛄ #MAGA, #WWG1WGA, #IrishWolfhounds	3169	4990	7045
1520146358	CandeCanteros	C	🐱🐶🖤👨🏻‍✈️✈❤🤸🏼‍♀️💃🎀👩🏼‍🔬💖	486	778	3187
702062019713830912	king06003	Robert Sauvage	Pour une planète  heureuse respectueuse. #TeamPatriotes:#AppeldeDomremy:\nhttps://www.islam-et-verite\npas de follow back systématique (dans les deux sens)	1096	823	141395
465055676	sbn1780	SLB		109	358	58706
1890202010	teckroat5	Tanya Eckroat	Never trumper, Liberal,Nurse (not currently working) immune compromised i.e. RA. Dog lover/rescuer currently have 20 dogs, no adoption events right now	1522	1884	94043
80746119	tannex78	TRISTAN TRIBBIANI	“The man who goes alone can start today; but he who travels with another must wait till that other is ready.” – Henry David Thoreau	251	118	4406
447497463	franciscobentle	francisco bentley		96	281	23151
83683660	prolechino	El Chuuuunior	Graduado en la sombra que vence la casa...	1831	1729	75711
205405826	raulsernaz	Raul	Viene el día en que triunfare en TODO lo que haga: La presencia de DIOS  me acompaña La Sangre de CRISTO me cubre El Espiritu Santo me guía. Q	358	959	27604
744234863008481280	StepTFup_KAIyle	Sarah 😝😝		75	231	36102
\.


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.countries (id, code, name) FROM stdin;
\.


--
-- Data for Name: hashtags; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hashtags (id, value) FROM stdin;
\.


--
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spatial_ref_sys (srid, auth_name, auth_srid, srtext, proj4text) FROM stdin;
\.


--
-- Data for Name: tweet_hashtags; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tweet_hashtags (id, hashtag_id, tweet_id) FROM stdin;
\.


--
-- Data for Name: tweet_mentions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tweet_mentions (id, account_id, tweet_id) FROM stdin;
\.


--
-- Data for Name: tweets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tweets (id, content, location, retweet_count, favorite_count, happened_at, author_id, country_id, parent_id) FROM stdin;
\.


--
-- Data for Name: geocode_settings; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY tiger.geocode_settings (name, setting, unit, category, short_desc) FROM stdin;
\.


--
-- Data for Name: pagc_gaz; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY tiger.pagc_gaz (id, seq, word, stdword, token, is_custom) FROM stdin;
\.


--
-- Data for Name: pagc_lex; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY tiger.pagc_lex (id, seq, word, stdword, token, is_custom) FROM stdin;
\.


--
-- Data for Name: pagc_rules; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY tiger.pagc_rules (id, rule, is_custom) FROM stdin;
\.


--
-- Data for Name: topology; Type: TABLE DATA; Schema: topology; Owner: postgres
--

COPY topology.topology (id, name, srid, "precision", hasz) FROM stdin;
\.


--
-- Data for Name: layer; Type: TABLE DATA; Schema: topology; Owner: postgres
--

COPY topology.layer (topology_id, layer_id, schema_name, table_name, feature_column, feature_type, level, child_id) FROM stdin;
\.


--
-- Name: accounts accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.accounts
    ADD CONSTRAINT accounts_pkey PRIMARY KEY (id);


--
-- Name: countries countries_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: hashtags hashtags_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hashtags
    ADD CONSTRAINT hashtags_pkey PRIMARY KEY (id);


--
-- Name: tweet_hashtags tweet_hashtags_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweet_hashtags
    ADD CONSTRAINT tweet_hashtags_pkey PRIMARY KEY (id);


--
-- Name: tweet_mentions tweet_mentions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweet_mentions
    ADD CONSTRAINT tweet_mentions_pkey PRIMARY KEY (id);


--
-- Name: tweets tweets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweets
    ADD CONSTRAINT tweets_pkey PRIMARY KEY (id);


--
-- Name: tweet_hashtags tweet_hashtags_hashtag_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweet_hashtags
    ADD CONSTRAINT tweet_hashtags_hashtag_id_fkey FOREIGN KEY (hashtag_id) REFERENCES public.hashtags(id) ON DELETE CASCADE;


--
-- Name: tweet_hashtags tweet_hashtags_tweet_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweet_hashtags
    ADD CONSTRAINT tweet_hashtags_tweet_id_fkey FOREIGN KEY (tweet_id) REFERENCES public.tweets(id) ON DELETE CASCADE;


--
-- Name: tweet_mentions tweet_mentions_account_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweet_mentions
    ADD CONSTRAINT tweet_mentions_account_id_fkey FOREIGN KEY (account_id) REFERENCES public.accounts(id) ON DELETE CASCADE;


--
-- Name: tweet_mentions tweet_mentions_tweet_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweet_mentions
    ADD CONSTRAINT tweet_mentions_tweet_id_fkey FOREIGN KEY (tweet_id) REFERENCES public.tweets(id) ON DELETE CASCADE;


--
-- Name: tweets tweets_author_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweets
    ADD CONSTRAINT tweets_author_id_fkey FOREIGN KEY (author_id) REFERENCES public.accounts(id) ON DELETE CASCADE;


--
-- Name: tweets tweets_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweets
    ADD CONSTRAINT tweets_country_id_fkey FOREIGN KEY (country_id) REFERENCES public.countries(id) ON DELETE CASCADE;


--
-- Name: tweets tweets_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tweets
    ADD CONSTRAINT tweets_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES public.tweets(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

