import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
	app.component('index-grouped-permissions', IndexField)
	app.component('detail-grouped-permissions', DetailField)
	app.component('form-grouped-permissions', FormField)
	// app.component('preview-grouped-permissions', PreviewField)
})
