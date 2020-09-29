FILENAME="postgres.sql"

echo "Dumping database"

docker-compose exec postgres bash -c "pg_dump -Upostgres -Wpostgres -p 5432 postgres > /dump/$FILENAME"

echo "Database dumped to '$FILENAME'"
