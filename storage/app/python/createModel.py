import tensorflow as tf
from tensorflow import keras

def create():
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
        metrics=[keras.metrics.SparseCategoricalAccuracy()]
    )

    return model
