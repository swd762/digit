import pandas as pd
import tensorflow as tf
from tensorflow import keras
from sklearn.model_selection import train_test_split

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
    tf.keras.layers.Dense(10)
])

model.compile(optimizer=keras.optimizers.RMSprop(),  # Optimizer
              # Минимизируемая функция потерь
              loss=keras.losses.SparseCategoricalCrossentropy(),
              # Список метрик для мониторинга
              metrics=[keras.metrics.SparseCategoricalAccuracy()])

history = model.fit(feature_train, label_train, batch_size=32,
                    epochs=10, validation_data=(feature_val, label_val))

print('\nhistory dict:', history.history)

# Оценим модель на тестовых данных, используя "evaluate"
print('\n# Оцениваем на тестовых данных')
results = model.evaluate(feature_test, label_test, batch_size=32)
print('test loss, test acc:', results)

# Сгенерируем прогнозы (вероятности - выходные данные последнего слоя)
# на новых данных с помощью "predict"
print('\n# Генерируем прогнозы для 3 образцов')
predictions = model.predict(feature_test[:3])
print('размерность прогнозов:', predictions.shape)
