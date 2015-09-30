--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

--
-- Data for Name: ae_parameters; Type: TABLE DATA; Schema: public; Owner: centrifuge
--

COPY ae_parameters (id, affiliate_id, vertical, country, extra, name) FROM stdin;
1	170317	skin	US	\N	\N
2	170315	skin	US	\N	Liam US Skin
\.


--
-- Name: ae_parameters_id_seq; Type: SEQUENCE SET; Schema: public; Owner: centrifuge
--

SELECT pg_catalog.setval('ae_parameters_id_seq', 30, true);


--
-- Data for Name: geos; Type: TABLE DATA; Schema: public; Owner: patrick
--

COPY geos (id, country, locale, data, name, variables) FROM stdin;
3	CA	en_CA	{"unit.length": "centimeter", "unit.weight": "kilogram"}	Canada	{}
4	AU	en_AU	{"unit.length": "centimeter", "unit.weight": "kilogram"}	Australia	{}
6	ZA	en_ZA	{"pronoun": "South African", "unit.length": "centimeter", "unit.weight": "kilogram"}	South Africa	{}
5	IT	it	{"unit.length": "centimeter", "unit.weight": "kilogram"}	Italy	{}
1	US	en_US	{"alt.name": "America", "unit.format": "long", "unit.length": "centimeter", "unit.weight": "pound"}	United States	{}
7	GB	en_GB	{"alt.name": "England", "unit.length": "centimeter", "unit.weight": "kilogram"}	United Kingdom	{}
\.


--
-- Name: geos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: patrick
--

SELECT pg_catalog.setval('geos_id_seq', 8, true);


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: centrifuge
--

COPY products (id, name, image_url, source, vertical) FROM stdin;
1	Simply Garcinia	simply.png	network	diet
2	Simply 2	native.png	network	diet
6	Advanced Keytone	advanced.jpg	network	diet
4	Test Product	test.png	network	skin
7	More Advanced	advanced.png	network	skin
\.


--
-- Data for Name: websites; Type: TABLE DATA; Schema: public; Owner: centrifuge
--

COPY websites (id, name, namespace, asset_dir, template_file) FROM stdin;
1	Womens Health Source	healthsource	static	base
2	Good Housekeeping	good_housekeeping	static	base
4	Healthy Mum	healthymum	static	base
5	Bingo Wings	womenshealthbod	static	base
6	New Health Source	new_lander	static	base
7	Bingo Wings	bingo_wings	static	base
8	Kelly Clarkson	kelly_clarkson	static	base
9	Menopause Women's Health Source	menopause	static	base
10	Menopause V2	menopause	static	version_2
11	Daily Newsfeed	daily_newsfeed	static	base
12	AARP Honey Boo Boo	aarp	static	base
13	Bingo Wings v2	bingo_v2	static	base
14	Paula Deen	paula_deen	static	base
15	Good Housekeeping - Ellen Exclusive	good_housekeeping2	static	base
\.


--
-- Data for Name: landers; Type: TABLE DATA; Schema: public; Owner: centrifuge
--

COPY landers (id, website_id, offer, product1_id, product2_id, param_id, notes, variants, geo_id, active, template_vars) FROM stdin;
1	1	adexchange	\N	\N	1	Health source with ad exchange	{}	1	t	{}
2	2	adexchange	\N	\N	1	Good housekeeping alleure	{}	1	t	{}
64	2	adexchange	\N	\N	1	Crazywhat	{"headlines": "crazy"}	1	t	{}
3	1	network	1	2	\N	Simpliy Garcinia	{}	1	f	{}
4	1	network	2	1	\N	Simpliy Garcinia Canada	{"headlines": "canada"}	1	f	{}
107	6	network	1	1	\N	Canada	{"headlines": "ca"}	3	t	{}
105	6	network	1	1	\N	US	{"headlines": "au"}	1	t	{}
108	6	network	1	1	\N	Austrailia	{}	4	t	{}
109	6	network	1	1	\N	Italy	{}	5	t	{}
110	6	network	1	1	\N	South Africa	{}	6	t	{}
111	6	network	1	1	\N	UK	{}	7	t	{}
115	11	network	1	2	\N	Daily Newsfeed	{}	1	t	{}
117	9	network	7	6	\N	Menopause ZA	{}	6	t	{}
118	9	adexchange	\N	\N	1	Menopause US	{}	1	t	{}
124	14	network	7	4	\N	Paula Deen	{}	1	t	{}
125	14	adexchange	\N	\N	1	PD	{}	1	t	{}
126	15	network	6	1	\N	GH2	{}	1	t	{}
\.


--
-- Name: landers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: centrifuge
--

SELECT pg_catalog.setval('landers_id_seq', 126, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: centrifuge
--

SELECT pg_catalog.setval('products_id_seq', 28, true);


--
-- Data for Name: routes; Type: TABLE DATA; Schema: public; Owner: centrifuge
--

COPY routes (id, url, lander_id) FROM stdin;
36	/za/bar	1
\.


--
-- Name: routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: centrifuge
--

SELECT pg_catalog.setval('routes_id_seq', 36, true);


--
-- Name: websites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: centrifuge
--

SELECT pg_catalog.setval('websites_id_seq', 12, true);


--
-- PostgreSQL database dump complete
--

