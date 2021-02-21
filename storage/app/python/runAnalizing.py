import pymysql
import sys
import json

import pandas as pd
import tensorflow as tf
from tensorflow import keras
from sklearn.model_selection import train_test_split

dateRange = sys.argv[1]
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

    # with connection.cursor() as cursor:
    #     cursor.execute("SELECT VERSION()")

    #     result = cursor.fetchone()

    #     print("Database version: {}".format(result))

    with connection.cursor() as cursor:
        sql = "SELECT * FROM `modules` WHERE `created_at` between %s and %s"
        cursor.execute(sql, (dates['dateFrom'], dates['dateTo']))

        result = cursor.fetchall()

        print(result)

        # Получаем данные за указанный период. В цикле оцениваем каждый набор данных. Записываем результат оценки обратно в таблицу


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


checkpoint_path = "training_1/cp.ckpt"
df = pd.read_csv('./data.csv')

features = df.drop('true', axis=1)
labels = df['true']

feature_train, feature_test, label_train, label_test = train_test_split(
    features, labels, test_size=0.33)

# Зарезервируем 100 примеров для валидации
feature_val = feature_train[-100:]  # последние 100
label_val = label_train[-100:]

feature_train = feature_train[:-100]  # все, кроме последних 100
label_train = label_train[:-100]

model = tf.keras.models.Sequential([
    tf.keras.layers.Dense(128, activation='relu'),
    tf.keras.layers.Dropout(0.2),
    tf.keras.layers.Dense(1)
])

model.compile(
    # optimizer=keras.optimizers.RMSprop(),  # Optimizer
    optimizer='adam',  # Optimizer
              # Минимизируемая функция потерь
              loss=keras.losses.SparseCategoricalCrossentropy(),
              # Список метрик для мониторинга
              metrics=[keras.metrics.SparseCategoricalAccuracy()])

# # Оценим необученную модель на тестовых данных, используя "evaluate"
# print('\n# Оцениваем на тестовых данных')
# results = model.evaluate(feature_test, label_test, batch_size=32)
# print('test loss, test acc:', results)

model.load_weights(checkpoint_path)

# Оценим модель на тестовых данных, используя "evaluate"
print('\n# Оцениваем на тестовых данных')
results = model.evaluate(feature_test, label_test, batch_size=32)
print('test loss, test acc:', results)


# Сгенерируем прогнозы (вероятности - выходные данные последнего слоя)
# на новых данных с помощью "predict"
print('\n# Генерируем прогнозы для 3 образцов')
predictions = model.predict(feature_test[:3])
print('размерность прогнозов:', predictions.shape)
print('прогнозы:', predictions)


probability_model = tf.keras.Sequential([
    model,
    tf.keras.layers.Softmax()
])


probability_model(feature_test[:5])
