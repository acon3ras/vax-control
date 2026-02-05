# Changelog

Todas las mejoras notables de este proyecto serán documentadas en este archivo.

## [1.1.0] - Mejoras de Seguridad y UX (2026-01-12)
### Seguridad
- **Corrección de Lógica Crítica:** Eliminada dependencia de ID fijo (1) para la ubicación principal ("Hospital"). Ahora el sistema busca dinámicamente por tipo o nombre, aumentando la robustez ante cambios en la base de datos.
- **Auditoría de Seguridad:** Verificación exitosa de protecciones contra condición de carrera (`lockForUpdate`) y protección CSRF.

### Experiencia de Usuario (UX)
- **Notificaciones Modernas:** Reemplazo de alertas intrusivas (`alert()`) por un sistema de notificaciones "Toast" (burbujas) no bloqueantes.
- **Feedback de Éxito:** Corrección para asegurar que los mensajes de éxito ("Inventario actualizado") sean visibles tras cerrar las ventanas modales.

## [1.0.0] - Lanzamiento Oficial (2026-01-06)
### Añadido
- **Gestión de Cuarentena Parcial:** Sistema para mover cantidades específicas de vacunas a bodega de cuarentena.
- **Visualización Dual de Stock:** Modales de ajuste muestran saldo "Disponible" y "En Cuarentena" simultáneamente.
- **Liberación Inteligente:** Funcionalidad para liberar stock de cuarentena con detección automática de lotes.
- **Validación de Inventario:** Controles estrictos para evitar saldos negativos en movimientos entre bodegas.
- **Dashboard (Mejoras Visuales):**
    - Se actualizó el ordenamiento de la lista "Estado de Stock por Vacuna".
    - Ahora prioriza vacunas con stock activo y las ordena por fecha de último movimiento (más reciente primero).
    - Las vacunas sin stock aparecen al final.
    - **Actualización Automática:** El dashboard ahora se refresca automáticamente cada 10 segundos para reflejar cambios de otros usuarios.
- **SQL Script:** Generado script no destructivo para carga inicial de stock.
- **Interfaz Optimizada:** Ventanas modales compactas con cierre automático y refresco de datos en tiempo real.
- **Dashboard Operativo:** Tablas de control con columnas dedicadas para stock activo y bloqueado.
