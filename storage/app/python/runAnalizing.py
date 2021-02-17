import pymysql
import sys
import json

# dateRange = sys.argv[1]
dates = json.loads(sys.argv[1])

print(dates)

print("true")


connection = pymysql.connect(host='localhost',
                             user='root',
                             password='root',
                             database='digit',
                             cursorclass=pymysql.cursors.DictCursor)

with connection:
    # with connection.cursor() as cursor:
    #     # Create a new record
    #     sql = "INSERT INTO `users` (`email`, `password`) VALUES (%s, %s)"
    #     cursor.execute(sql, ('webmaster@python.org', 'very-secret'))

    # # connection is not autocommit by default. So you must commit to save
    # # your changes.
    # connection.commit()

    # with connection.cursor() as cursor:
    # Read a single record
    # sql = "SELECT `id`, `password` FROM `users` WHERE `email`=%s"
    # cursor.execute(sql, ('webmaster@python.org',))
    # result = cursor.fetchone()
    # print(result)

    with connection.cursor() as cursor:
        cursor.execute("SELECT VERSION()")

        result = cursor.fetchone()

        print("Database version: {}".format(result))

# import psycopg2
# from sqlalchemy import create_engine

# # conn = create_engine('postgresql://user:password@host:port/dbname')
# conn = create_engine('postgresql+psycopg2://user:password@host:port/dbname')
# cur = conn.cursor()

# qry = """
# SELECT * from table_name WHERE fieldname LIKE(:param_A)
# """
# params=("xxxx'A'-%", )

# cur.execute(qry, params=params)

# for row in cur.fetchall():
#     #print(row)
#     #...
