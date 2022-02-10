# [Moodle Logstore xAPI](https://moodle.org/plugins/view/logstore_xapi)
> Emits [xAPI](https://github.com/adlnet/xAPI-Spec/blob/master/xAPI.md) statements using the [Moodle](https://moodle.org/) Logstore.

- Install the plugin using [our zip installation guide](/docs/install-with-zip.md).
- Process events before the plugin was installed using [our historical events guide](/docs/historical-events.md).
- Ask questions via the [Github issue tracker](https://github.com/xAPI-vle/moodle-logstore_xapi/issues).
- Report bugs and suggest features with the [Github issue tracker](https://github.com/xAPI-vle/moodle-logstore_xapi/issues).
- View the supported events in [our `get_event_function_map` function](/src/transformer/get_event_function_map.php).
- Change existing statements for the supported events using [our change statements guide](/docs/change-statements.md).
- Create statements using [our new statements guide](/docs/new-statements.md).

# Adaptación para EVIA

Se modificaron dos archivos dentro del plugin:
- src/transformer/events/mod_assign/assignment_graded.php
- src/transformer/repos/MoodleRepository.php

El plugin envía la información al LRS seleccionado. Cuando el evento 'calificar' es accionado, el LRS recibe las Tags o Etiquetas y las clasifica como un 'statement de xAPI'. Enviando el nombre de cada Etiqueta y su Id.

La nomenclatura dentro del LRS es la siguiente:
- /competence-ids
- /competence-map-id
