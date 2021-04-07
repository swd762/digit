import pymysql
import sys
import json

import pandas as pd
import tensorflow as tf
from tensorflow import keras
from sklearn.model_selection import train_test_split
import createModel

# Загружаем обученную модель из папки
checkpoint_path = "training_1/cp.ckpt"

model = createModel.create()
model.load_weights(checkpoint_path)

# Получаем период за который необходимо оценить данные
dateRange = sys.argv[1]
dates = json.loads(sys.argv[1])

print(dates)

connection = pymysql.connect(host='localhost',
                             user='root',
                             password='root',
                             database='digit',
                             cursorclass=pymysql.cursors.DictCursor)

# Получаем данные за указанный период.
# В цикле оцениваем каждый набор данных.
# Записываем результат оценки обратно в таблицу

with connection:
    with connection.cursor() as cursor:
        sql = "SELECT `id`, `temperature`, `bend` FROM `module_data` WHERE `created_at` between %s and %s"
        cursor.execute(sql, (dates['dateFrom'], dates['dateTo']))

        data = cursor.fetchall()

        print(data)

for record in data:
    recordForAssessing = record.drop('id', axis=1)

    predictions = model.predict(recordForAssessing)
    print('размерность прогнозов:', predictions.shape)
    print('прогнозы:', predictions)

    with connection:
        with connection.cursor() as cursor:
            # Сохраняем результат оценки
            sql = "UPDATE `module_data` SET `is_real` = %s WHERE `id` = %s"
            cursor.execute(sql, (predictions[0], record.id))

        # connection is not autocommit by default. So you must commit to save
        # your changes.
        connection.commit()


# probability_model = tf.keras.Sequential([
#     model,
#     tf.keras.layers.Softmax()
# ])


# probability_model(feature_test[:5])
