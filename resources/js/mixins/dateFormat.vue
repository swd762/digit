<script>
export default {
  methods: {
    mtz: function (date, format) {
      moment.locale("ru");

      if (!date) {
        return "";
      }

      return moment.utc(date).format(format);
    },
  },
};
</script>
