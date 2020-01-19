Nova.booting((Vue, router, store) => { 
  router.addRoutes([
	  {
	    name: 'create.wizard',
	    path: '/resources/wizard/:resourceName/new',
	    component: require('./components/create'),
	    props: route => {
	      return {
	        resourceName: route.params.resourceName,
	        viaResource: route.query.viaResource,
	        viaResourceId: route.query.viaResourceId,
	        viaRelationship: route.query.viaRelationship,
	      }
	    },
	  },
	  {
	    name: 'edit.wizard',
	    path: '/resources/wizard/:resourceName/:resourceId/edit',
	    component: require('./components/update'),
	    props: route => {
	      return {
	        resourceName: route.params.resourceName,
	        resourceId: route.params.resourceId,
	        viaResource: route.query.viaResource,
	        viaResourceId: route.query.viaResourceId,
	        viaRelationship: route.query.viaRelationship,
	      }
	    },
	  },
  ])

  router.beforeEach((to, from, next) => {  
  	var resource = _.find(Nova.config.resources, r => r.uriKey == to.params.resourceName);
  	var editing = to.name.match(/^create|edit/) && ! to.name.match(/\.wizard$/); 

  	if(! editing || resource === undefined || resource.wizard !== true) { 
  		return next();
  	} 

  	console.log('redirecte to armincms wizard (:')

		router.push({
			name: to.name + '.wizard',
			params: to.params,
			query: to.query
		}) 
  })
})
