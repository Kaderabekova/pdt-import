SELECT * FROM tweets WHERE id = '1289380305023848448';

SELECT * FROM tweets WHERE location IS NOT NULL;

DELETE FROM tweets WHERE id = '1289380305023848448';

SELECT ST_AsGeoJSON(location) FROM tweets WHERE id = '1289380490193956865';