<script>
import { Errors } from 'laravel-nova'
export default {

  data: () => ({
    currentStep : null,
    lastRetrievedAt: null,
    wasValidatedResource: false
  }),

  created() { 
    console.log('welcome to armincms wizard (:')
  },

  methods: { 
    shouldDisplayPanel(panel) {  
      return panel.step === undefined || panel.step === this.current;
    },

    stepTo(step) {
      this.currentStep = step; 
    }, 

    stepViaAttributes(attributes) {  
      this.stepTo(
        this.firstStepViaAttributes(attributes).step
      )
    },

    next() {
      this.stepTo(this.nextStep); 
    },

    previous() {
      this.stepTo(this.previousStep); 
    }, 

    panelClasses(panel) {
      return this.shouldDisplayPanel(panel) ? [] : ['hidden']
    },

    isPassed(panel) {  
      return this.shouldDisplayPanel(panel) || this.stepPassed(panel.step);
    },

    stepPassed(step) {
      return this.steps.indexOf(step) <= this.steps.indexOf(this.current)
    },

    async validateViaUpdateResourceAndContinueEditing() {
      this.wasValidatedResource = true  

      await this.validateResource()

      this.wasValidatedResource = false
    },  

    /**
     * Update the resource using the provided data.
     */
    async validateResource() {
      this.isWorking = true

      if (this.$refs.form.reportValidity()) {
        try {
          await this.validateRequest(this.current)

          Nova.success(
            this.__('The :step was validated!', {
              step: this.currentPanel.name,
            })
          )   

          this.next() 
        } catch (error) { 
          console.log(error)

          if (error.response.status == 422) {
            this.validationErrors = new Errors(error.response.data.errors)
            Nova.error(this.__('There was a problem submitting the form.'))

            this.stepViaAttributes(
              _.keys(error.response.data.errors)
            );
          }

          if (error.response.status == 409) {
            Nova.error(
              this.__(
                'Another user has updated this resource since this page was loaded. Please refresh the page and try again.'
              )
            )
          }  
        }
      } 

      this.isWorking = false
    }, 

    firstStepViaAttributes(attributes) {
      return this.stepsWithFields.filter(panel => { 
        return panel.fields.filter(field => {
          return attributes.indexOf(field.attribute) >= 0; 
        }).length;
      }).shift()
    },
  },

  computed: {
    steps() {   
      return this.panels.map(panel => panel.step).filter((step, index, self) => { 
        return step && self.indexOf(step) >= index
      }); 
    },

    current() {
      return this.currentStep === null ? this.firstStep : this.currentStep
    },

    firstStep() { 
      return this.steps[0];
    },

    lastStep() {
      return this.steps[this.steps.length - 1];
    },

    previousStep() { 
      return this.steps[this.currentStepIndex - 1]
    },

    nextStep() {  
      return this.steps[this.currentStepIndex + 1]
    },

    currentStepIndex() {
      return this.steps.indexOf(this.current);
    },

    wizardDone() {
      return this.current === this.lastStep
    },

    wizardFirst() {
      return this.current === this.firstStep
    },

    currentPanel() {
      return _.find(this.panels, panel => panel.step === this.current);
    },

    /**
     * Create the form data for creating the resource.
     */
    validateResourceFormData() { 
      return _.tap(new FormData(), formData => {
        _(this.fields).each(field => {
          field.fill(formData)
        })

        formData.append('_retrieved_at', this.lastRetrievedAt)
      })
    },  

    stepsWithFields() {
      return _.map(this.panels, panel => {
        return {
          name: panel.name,
          step: panel.step,
          fields: _.filter(this.fields, field => field.panel == panel.name),
        }
      })
    },
  },
}
</script>
