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
-- Data for Name: ae_parameters; Type: TABLE DATA; Schema: public; Owner: patrick
--

COPY ae_parameters (id, affiliate_id, vertical, country, extra, name) FROM stdin;
1	170317	skin	US	\N	\N
\.


--
-- Name: ae_parameters_id_seq; Type: SEQUENCE SET; Schema: public; Owner: patrick
--

SELECT pg_catalog.setval('ae_parameters_id_seq', 1, true);


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: patrick
--

COPY products (id, name, image_url) FROM stdin;
1	Simply Garcinia	/healthsource/static/simply.png
2	Simply 2	/healthsource/static/simply.png
\.


--
-- Data for Name: websites; Type: TABLE DATA; Schema: public; Owner: patrick
--

COPY websites (id, name, template, assets) FROM stdin;
1	Womens Health Source	healthsource/healthpure.php	/healthsource/static
2	Good Housekeeping	good_housekeeping/alleure.php	/good_housekeeping/index_files
\.


--
-- Data for Name: landers; Type: TABLE DATA; Schema: public; Owner: patrick
--

COPY landers (id, website_id, offer, product1_id, product2_id, param_id, notes, tracking_tags) FROM stdin;
1	1	adexchange	\N	\N	1	Health source with ad exchange	{'googleAnalytics','perfectAudience'}
2	2	adexchange	\N	\N	1	Good housekeeping alleure	{'googleAnalytics','perfectAudience'}
3	1	network	1	2	\N	Simpliy Garcinia	{'googleAnalytics','perfectAudience'}
\.


--
-- Name: landers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: patrick
--

SELECT pg_catalog.setval('landers_id_seq', 3, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: patrick
--

SELECT pg_catalog.setval('products_id_seq', 2, true);


--
-- Data for Name: routes; Type: TABLE DATA; Schema: public; Owner: patrick
--

COPY routes (id, url, lander_id) FROM stdin;
1	/pure/garcinia	3
2	/blog/wow	2
\.


--
-- Name: routes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: patrick
--

SELECT pg_catalog.setval('routes_id_seq', 2, true);


--
-- Name: websites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: patrick
--

SELECT pg_catalog.setval('websites_id_seq', 3, true);


--
-- PostgreSQL database dump complete
--

